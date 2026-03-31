import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';

const STORAGE_KEYS = {
    active: 'kyusify_seller_onboarding_active',
    step: 'kyusify_seller_onboarding_step',
    completed: 'kyusify_seller_onboarding_completed',
};

const STEP_KEYS = {
    DASHBOARD_CARDS: 'dashboard-cards',
    DASHBOARD_CHARTS: 'dashboard-charts',
    PROFILE_SECTION: 'profile-section',
    PROFILE_DOCUMENT: 'profile-document',
    EDIT_VERIFICATION: 'edit-verification',
    SIDEBAR_MODULES: 'sidebar-modules',
    FINAL_CHECKLIST: 'final-checklist',
};

function getConfig() {
    return window.sellerOnboardingConfig || null;
}

function currentPath() {
    return window.location.pathname;
}

function setProgress(stepKey) {
    localStorage.setItem(STORAGE_KEYS.active, '1');
    localStorage.setItem(STORAGE_KEYS.step, stepKey);
}

function resetProgress() {
    localStorage.removeItem(STORAGE_KEYS.active);
    localStorage.removeItem(STORAGE_KEYS.step);
}

async function completeOnboarding(config) {
    localStorage.setItem(STORAGE_KEYS.completed, '1');
    resetProgress();

    if (!config?.completeUrl) {
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        return;
    }

    try {
        await fetch(config.completeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                Accept: 'application/json',
            },
            body: JSON.stringify({ completed: true }),
        });
    } catch (_) {
        // Keep local completion even when network persistence fails.
    }
}

function goTo(config, stepKey, url) {
    setProgress(stepKey);
    window.location.href = url;
}

function stepButtons({ back = null, next = null, tour, config }) {
    const buttons = [
        {
            text: 'Skip',
            classes: 'shepherd-button-secondary',
            action: async () => {
                await completeOnboarding(config);
                tour.cancel();
            },
        },
    ];

    if (back) {
        buttons.push({
            text: 'Back',
            classes: 'shepherd-button-secondary',
            action: back,
        });
    }

    if (next) {
        buttons.push({
            text: 'Next',
            action: next,
        });
    }

    return buttons;
}

function makeTour(config) {
    return new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            cancelIcon: { enabled: true },
            scrollTo: { behavior: 'smooth', block: 'center' },
            classes: 'kyusify-seller-tour',
        },
    });
}

function addDashboardSteps(tour, config) {
    tour.addStep({
        id: STEP_KEYS.DASHBOARD_CARDS,
        title: 'Dashboard Summary Cards',
        text: "These cards show your store statistics and activity: Listed Products, Pending Orders, Total Revenue, and Customer Inquiries.",
        attachTo: { element: '[data-tour="dashboard-summary-cards"]', on: 'bottom' },
        buttons: stepButtons({
            tour,
            config,
            next: () => {
                setProgress(STEP_KEYS.DASHBOARD_CHARTS);
                tour.next();
            },
        }),
    });

    tour.addStep({
        id: STEP_KEYS.DASHBOARD_CHARTS,
        title: 'Sales Analytics Charts',
        text: 'These charts will show your sales performance and order analytics once your store is active.',
        attachTo: { element: '[data-tour="dashboard-charts"]', on: 'bottom' },
        buttons: stepButtons({
            back: () => tour.back(),
            tour,
            config,
            next: () => goTo(config, STEP_KEYS.PROFILE_SECTION, config.profileUrl),
        }),
    });
}

function addProfileSteps(tour, config) {
    tour.addStep({
        id: STEP_KEYS.PROFILE_SECTION,
        title: 'Store Profile',
        text: 'This is where you manage your store information and keep your profile updated.',
        attachTo: { element: '[data-tour="profile-section"]', on: 'bottom' },
        buttons: stepButtons({
            back: () => goTo(config, STEP_KEYS.DASHBOARD_CHARTS, config.dashboardUrl),
            tour,
            config,
            next: () => {
                setProgress(STEP_KEYS.PROFILE_DOCUMENT);
                tour.next();
            },
        }),
    });

    tour.addStep({
        id: STEP_KEYS.PROFILE_DOCUMENT,
        title: 'Student Identity Document',
        text: 'Upload your QCU ID here. This document is required for account approval and store verification.',
        attachTo: { element: '[data-tour="student-id-document"]', on: 'top' },
        buttons: stepButtons({
            back: () => tour.back(),
            tour,
            config,
            next: () => goTo(config, STEP_KEYS.EDIT_VERIFICATION, config.editProfileUrl),
        }),
    });
}

function addEditSteps(tour, config) {
    tour.addStep({
        id: STEP_KEYS.EDIT_VERIFICATION,
        title: 'Student Verification Details',
        text: 'Complete these student verification fields to help admin review and approve your seller account.',
        attachTo: { element: '[data-tour="student-verification-fields"]', on: 'top' },
        buttons: stepButtons({
            back: () => goTo(config, STEP_KEYS.PROFILE_DOCUMENT, config.profileUrl),
            tour,
            config,
            next: () => {
                setProgress(STEP_KEYS.SIDEBAR_MODULES);
                tour.next();
            },
        }),
    });

    tour.addStep({
        id: STEP_KEYS.SIDEBAR_MODULES,
        title: 'Seller Portal Modules',
        text: 'Use Products to add and manage items, Orders to process purchases, Inquiries to chat with customers, Feedback to monitor ratings, and Sales Reports to track performance. These modules unlock fully once admin approves your account.',
        attachTo: { element: '[data-tour="seller-sidebar"]', on: 'right' },
        buttons: stepButtons({
            back: () => tour.back(),
            tour,
            config,
            next: () => goTo(config, STEP_KEYS.FINAL_CHECKLIST, config.dashboardUrl),
        }),
    });
}

function addFinalStep(tour, config) {
    tour.addStep({
        id: STEP_KEYS.FINAL_CHECKLIST,
        title: 'You Are Almost Ready',
        text: `
            <ul style="margin-left: 1rem; list-style: disc;">
                <li>Complete your store profile</li>
                <li>Upload your student ID</li>
                <li>Wait for admin approval</li>
                <li>Add your products</li>
            </ul>
        `,
        attachTo: { element: '[data-tour="dashboard-summary-cards"]', on: 'bottom' },
        buttons: [
            {
                text: 'Skip',
                classes: 'shepherd-button-secondary',
                action: async () => {
                    await completeOnboarding(config);
                    tour.cancel();
                },
            },
            {
                text: 'Back',
                classes: 'shepherd-button-secondary',
                action: () => goTo(config, STEP_KEYS.SIDEBAR_MODULES, config.editProfileUrl),
            },
            {
                text: 'Go to Store Profile',
                classes: 'shepherd-button-secondary',
                action: async () => {
                    await completeOnboarding(config);
                    window.location.href = config.profileUrl;
                },
            },
            {
                text: 'Finish Tour',
                action: async () => {
                    await completeOnboarding(config);
                    tour.complete();
                },
            },
        ],
    });
}

function startOnboardingTour() {
    const config = getConfig();
    if (!config?.enabled) {
        return;
    }

    const alreadyCompleted =
        config.serverCompleted ||
        localStorage.getItem(STORAGE_KEYS.completed) === '1';

    const active = localStorage.getItem(STORAGE_KEYS.active) === '1';
    const requestedStep = localStorage.getItem(STORAGE_KEYS.step);

    if (alreadyCompleted) {
        resetProgress();
        return;
    }

    const tour = makeTour(config);
    addDashboardSteps(tour, config);
    addProfileSteps(tour, config);
    addEditSteps(tour, config);
    addFinalStep(tour, config);

    const path = currentPath();
    let startStep = null;

    if (path === config.dashboardPath) {
        startStep = active && requestedStep ? requestedStep : STEP_KEYS.DASHBOARD_CARDS;
    } else if (path === config.profilePath && active) {
        startStep = requestedStep || STEP_KEYS.PROFILE_SECTION;
    } else if (path === config.editProfilePath && active) {
        startStep = requestedStep || STEP_KEYS.EDIT_VERIFICATION;
    }

    if (!startStep || !tour.getById(startStep)) {
        return;
    }

    const stepOrder = Object.values(STEP_KEYS);
    if (stepOrder.includes(startStep)) {
        setProgress(startStep);
    }

    tour.start();

    if (startStep !== STEP_KEYS.DASHBOARD_CARDS) {
        tour.show(startStep);
    }
}

document.addEventListener('DOMContentLoaded', startOnboardingTour);
