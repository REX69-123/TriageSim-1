<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TriageSim - Interactive First Aid and Triage Learning Platform</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            bg: '#eef9f5',
                            mint: '#d1f2e9',
                            mintLight: '#e0f4f0',
                            teal: '#0096a5',
                            tealDark: '#087a86',
                            tealDeep: '#065f68',
                            accent: '#00a887',
                            dark: '#1e293b',
                            muted: '#64748b'
                        },
                        triage: {
                            red: '#ef4444',
                            yellow: '#eab308',
                            green: '#22c55e',
                            black: '#1e293b'
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #eef9f5;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .premium-card {
            background: #ffffff;
            border: 2px solid #d1f2e9;
            border-radius: 16px;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -4px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 150, 165, 0.15);
            border-color: #0096a5;
        }

        .view-frame {
            display: none;
            animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .view-frame.active {
            display: block;
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #eef9f5;
        }

        ::-webkit-scrollbar-thumb {
            background: #c3eade;
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0096a5;
        }
    </style>
</head>

<body class="min-height-screen flex flex-col items-center justify-center p-4 md:p-8">

    <header class="w-full max-w-6xl mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3 cursor-pointer" onclick="showView('homeView')">
            <div
                class="w-10 h-10 bg-brand-accent rounded-full flex items-center justify-center text-white shadow-md shadow-brand-accent/20">
                <i data-lucide="check" class="w-5 h-5 stroke-[3]"></i>
            </div>
            <div>
                <span class="text-2xl font-bold tracking-tight text-brand-teal">TriageSim</span>
                <span
                    class="hidden md:inline-block ml-2 px-2.5 py-0.5 text-xs font-semibold bg-brand-mintLight text-brand-tealDark rounded-full">v2.0
                    Stable</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleHelpModal()"
                class="w-10 h-10 rounded-full border-2 border-brand-mint bg-white hover:bg-brand-mintLight text-brand-tealDark flex items-center justify-center transition-colors">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
            </button>
        </div>
    </header>

    <div id="helpModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-lg w-full overflow-hidden shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div
                class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex justify-between items-center">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i data-lucide="book-open" class="w-5 h-5"></i> START Triage Reference Sheet
                </h3>
                <button onclick="toggleHelpModal()" class="text-white/80 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 text-sm text-brand-dark overflow-y-auto max-h-[70vh]">
                <p class="font-medium text-brand-tealDark">Simple Triage and Rapid Treatment (START) Algorithm Rules:
                </p>
                <div class="space-y-3">
                    <div class="p-3 bg-red-50 border-l-4 border-triage-red rounded-r-lg">
                        <strong class="text-triage-red block">Immediate (Red Tag)</strong>
                        Respirations &gt; 30/min, Capillary Refill Time &gt; 2 seconds (or absent radial pulse), or
                        unable to follow simple commands.
                    </div>
                    <div class="p-3 bg-yellow-50 border-l-4 border-triage-yellow rounded-r-lg">
                        <strong class="text-triage-yellow block">Delayed (Yellow Tag)</strong>
                        Spontaneous respirations present, Rate &lt; 30/min, perfusion metrics within normal limits (CRT
                        &lt; 2s), but unable to walk.
                    </div>
                    <div class="p-3 bg-green-50 border-l-4 border-triage-green rounded-r-lg">
                        <strong class="text-triage-green block">Minor (Green Tag)</strong>
                        All patients who can walk (the "walking wounded"). High mobility indicators.
                    </div>
                    <div class="p-3 bg-slate-50 border-l-4 border-triage-black rounded-r-lg">
                        <strong class="text-brand-dark block">Deceased (Black Tag)</strong>
                        No spontaneous ventilation, even after manual airway opening.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="exitConfirmModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-sm w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <h3 class="text-lg font-bold text-brand-dark mb-2 flex items-center gap-2 text-red-500">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i> Exit Simulation?
            </h3>
            <p class="text-sm text-brand-muted mb-6">
                Are you sure you want to quit this scenario? Your current progress and telemetry metrics will be lost.
            </p>
            <div class="flex gap-3">
                <button onclick="closeExitModal()"
                    class="flex-1 py-2.5 px-4 border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-bg transition-colors text-sm">Cancel</button>
                <button onclick="executeExitSimulation()"
                    class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors text-sm">Exit
                    Scenario</button>
            </div>
        </div>
    </div>

    <div id="resultModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-2xl w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="flex items-center gap-2 text-brand-teal mb-2">
                <i data-lucide="activity" class="w-5 h-5"></i>
                <h3 class="text-lg font-bold text-brand-dark">Case Evaluation &amp; Path Review</h3>
            </div>
            <p class="text-xs text-brand-muted mb-4 pb-4 border-b border-brand-bg">
                Review your path results, errors made, and correct alternative parameters.
            </p>
            <div id="resultModalContent" class="space-y-4 mb-6 max-h-[50vh] overflow-y-auto pr-2"></div>
            <div id="resultModalButtonWrapper" class="flex gap-3 border-t border-brand-mint pt-4"></div>
        </div>
    </div>

    <div id="lockWarningModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-md w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="flex items-start gap-3 mb-4">
                <i data-lucide="alert-circle" class="w-6 h-6 text-red-500"></i>
                <div>
                    <h3 class="text-lg font-bold text-brand-dark">Proceed to Next Quiz</h3>
                    <p class="text-xs text-brand-muted">This will be locked if you proceed to the next quiz.</p>
                </div>
            </div>
            <p class="text-sm text-brand-muted mb-6">
                If you continue, this quiz will be locked and the reattempt option will be disabled. Your answer review
                will still be available.
            </p>
            <div class="flex gap-3">
                <button onclick="closeLockWarningModal()"
                    class="flex-1 py-2.5 px-4 border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-bg transition-colors text-sm">Cancel</button>
                <button onclick="confirmLockAndReview()"
                    class="flex-1 py-2.5 px-4 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm">Proceed</button>
            </div>
        </div>
    </div>

    <div id="saveConfirmModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-md w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="flex items-start gap-3 mb-4">
                <i data-lucide="shield-check" class="w-6 h-6 text-brand-teal"></i>
                <div>
                    <h3 class="text-lg font-bold text-brand-dark">Confirm Save</h3>
                    <p class="text-xs text-brand-muted">Lock this case and submit your session data.</p>
                </div>
            </div>
            <p class="text-sm text-brand-muted mb-6">
                Your answer review has been recorded. Confirming will lock this quiz and save the session, preventing
                further reattempts until tomorrow.
            </p>
            <div class="flex gap-3">
                <button onclick="closeSaveConfirmModal()"
                    class="flex-1 py-2.5 px-4 border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-bg transition-colors text-sm">Go
                    Back</button>
                <button onclick="confirmSaveSession()"
                    class="flex-1 py-2.5 px-4 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm">Lock
                    & Save</button>
            </div>
        </div>
    </div>

    <div id="sessionSavedModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-md w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="flex items-start gap-3 mb-4">
                <i data-lucide="check-circle" class="w-6 h-6 text-brand-teal"></i>
                <div>
                    <h3 class="text-lg font-bold text-brand-dark">Session Saved</h3>
                    <p class="text-xs text-brand-muted">Your quiz progress has been recorded successfully.</p>
                </div>
            </div>
            <p id="sessionSavedMessage" class="text-sm text-brand-muted mb-6"></p>
            <div class="flex gap-3">
                <button onclick="closeSessionSavedModal()"
                    class="flex-1 py-2.5 px-4 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm">Continue</button>
            </div>
        </div>
    </div>

    <div id="reviewModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-3xl w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3 text-brand-teal">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                    <div>
                        <h3 class="text-lg font-bold">Review Answers</h3>
                        <p class="text-xs text-brand-muted">Preview your accuracy and decision latency.</p>
                    </div>
                </div>
                <button onclick="closeReviewModal()" class="text-brand-dark hover:text-brand-teal">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div id="reviewModalContent" class="space-y-4 max-h-[60vh] overflow-y-auto pr-2"></div>
            <div class="flex gap-3 mt-4">
                <button onclick="closeReviewModal()"
                    class="flex-1 py-2.5 px-4 border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-bg transition-colors text-sm">Close</button>
            </div>
        </div>
    </div>

    <div id="susModal"
        class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-2xl border-2 border-brand-mint max-w-2xl w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2 text-brand-teal">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i> System Usability Scale (SUS)
                </h3>
                <span
                    class="px-3 py-1 bg-brand-accent/10 text-brand-accent text-xs font-bold rounded-full uppercase tracking-wider">Day
                    3 Final Requirement</span>
            </div>
            <p class="text-xs text-brand-muted mb-4 pb-4 border-b border-brand-bg">
                Methodology Step 4: Please rate the platform's student-friendliness. (Scale: 1 = Strongly Disagree, 5 =
                Strongly Agree). You must complete this to submit your final session data.
            </p>
            <div id="susQuestionsWrapper" class="space-y-4 mb-6 max-h-[50vh] overflow-y-auto pr-2"></div>
            <div class="flex gap-3 border-t border-brand-mint pt-4">
                <button onclick="submitFinalSessionWithSUS()"
                    class="w-full py-3 px-4 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm">
                    Submit Survey & Conclude Testing Protocol
                </button>
            </div>
        </div>
    </div>

    <main id="homeView" class="view-frame active w-full max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-brand-dark mb-4">
                Branching-Scenario <span class="text-brand-teal">Emergency Triage</span>
            </h1>
            <p class="text-lg text-brand-muted max-w-2xl mx-auto font-medium">
                Bridging the clinical theory-practice gap through interactive Finite State Machine (FSM) models and
                precise decision latency telemetry.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="premium-card premium-card-hover p-8 cursor-pointer flex flex-col justify-between"
                onclick="showView('consentView')">
                <div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-brand-mintLight flex items-center justify-center text-brand-teal mb-6">
                        <i data-lucide="play-circle" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-brand-dark mb-3">Student Simulator</h2>
                    <p class="text-brand-muted mb-6 leading-relaxed">
                        Enter a safe-to-fail clinical scenario. Your decision accuracy, path variations, and hesitation
                        patterns will be tracked dynamically.
                    </p>
                </div>
                <div class="space-y-3 pt-4 border-t border-brand-mintLight">
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="clock" class="w-4 h-4 text-brand-teal"></i> 3-Day Testing Protocol Enforced
                    </div>
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="activity" class="w-4 h-4 text-brand-teal"></i> Real-time Latency (L) Telemetry
                    </div>
                </div>
            </div>

            <div class="premium-card premium-card-hover p-8 cursor-pointer flex flex-col justify-between"
                onclick="showView('loginView')">
                <div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-brand-mintLight flex items-center justify-center text-brand-teal mb-6">
                        <i data-lucide="line-chart" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-brand-dark mb-3">Instructor Dashboard</h2>
                    <p class="text-brand-muted mb-6 leading-relaxed">
                        Access detailed learning analytics, map structural bottleneck heatmaps, and assess
                        individual/aggregate path optimization metrics.
                    </p>
                </div>
                <div class="space-y-3 pt-4 border-t border-brand-mintLight">
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="users" class="w-4 h-4 text-brand-teal"></i> Collective Class Rank Lists
                    </div>
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="flame" class="w-4 h-4 text-brand-teal"></i> Cognitive Hesitation Heatmaps
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <span
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-brand-mint text-xs font-semibold tracking-wider text-brand-tealDark uppercase rounded-full shadow-sm">
                <i data-lucide="database" class="w-3.5 h-3.5"></i> FSM State engine active
            </span>
        </div>
    </main>

    <main id="consentView" class="view-frame w-full max-w-2xl">
        <button onclick="showView('homeView')"
            class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Main Menu
        </button>

        <div class="premium-card p-8 bg-white">
            <div class="text-center mb-6">
                <span
                    class="bg-brand-mintLight text-brand-tealDark text-xs font-semibold px-3 py-1 rounded-full uppercase">Student
                    Research Gateway</span>
                <h2 class="text-2xl font-bold text-brand-dark mt-3">Digital Informed Consent Form</h2>
            </div>

            <div
                class="text-sm text-brand-muted space-y-4 max-h-72 overflow-y-auto p-5 bg-brand-bg rounded-lg border border-brand-mint mb-6">
                <p class="font-bold text-brand-dark">Project Title: TriageSim Branching-Scenario Simulation Engine</p>
                <p><strong>Introduction:</strong> You are being invited to participate in an evaluation study tracking
                    the efficacy of non-linear branching engines in medical triage training. Your participation is
                    entirely autonomous and voluntary.</p>
                <p><strong>Procedure:</strong> If you choose to participate, you will complete 3 simulated emergency
                    triage cases over the course of 3 days. The system will record background interaction telemetry
                    (decision hesitation times and path choices) to assess performance progression.</p>
                <p><strong>Data Confidentiality:</strong> All recorded metrics are processed solely for statistical
                    verification. Your identity traits will be decoupled from aggregate research papers.</p>
                <p><strong>Right to Withdraw:</strong> You maintain the unconditional right to abort or terminate your
                    simulation session at any timeline marker without administrative penalty.</p>
            </div>

            <div class="flex items-start mb-6 gap-3 p-4 bg-white border border-brand-mint rounded-xl">
                <input id="consentCheckbox" type="checkbox" onchange="toggleConsent(this)"
                    class="mt-1 w-5 h-5 text-brand-teal bg-gray-100 border-gray-300 rounded focus:ring-brand-teal">
                <label for="consentCheckbox" class="text-sm text-brand-dark font-medium select-none cursor-pointer">
                    I explicitly declare that I have read the conditions above and give my full consent to participate
                    in this study.
                </label>
            </div>

            <button id="btnAcceptConsent" onclick="acceptConsentForm()" disabled
                class="w-full py-3.5 px-4 bg-slate-300 text-slate-500 font-bold rounded-xl transition duration-200 cursor-not-allowed">
                Proceed to Profile Setup
            </button>
        </div>
    </main>

    <main id="studentSetupView" class="view-frame w-full max-w-md">
        <button onclick="showView('homeView')"
            class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Main Menu
        </button>

        <div class="premium-card overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <i data-lucide="user-cog" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold leading-tight">Student Profile Setup</h2>
                    <p class="text-xs text-brand-mintLight">Fields marked with (*) are required</p>
                </div>
            </div>

            <div class="p-6 space-y-5 bg-white">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Student Name
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="studentInputName" required
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all placeholder-brand-muted/50"
                        placeholder="e.g. John Doe">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Student ID
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="studentInputId" oninput="checkStudentHistory(this)" required
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all placeholder-brand-muted/50"
                        placeholder="e.g. STU2026118">
                    <p class="text-[10px] text-brand-muted mt-1 font-medium">Entering your ID will automatically update
                        your scenario availability.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Cohort /
                        Section</label>
                    <select id="studentInputCohort"
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all bg-white">
                        <option value="NURS-301-A">Section NURS-301-A (Mon/Wed)</option>
                        <option value="NURS-301-B">Section NURS-301-B (Tue/Thu)</option>
                        <option value="Independent">Independent Learner</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Methodology
                        Day</label>
                    <select id="studentInputDay" onchange="updateScenarioOptionsByDay()"
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all bg-white">
                        <option value="1">Day 1</option>
                        <option value="2" disabled>Day 2</option>
                        <option value="3" disabled>Day 3</option>
                    </select>
                    <p class="text-[10px] text-brand-muted mt-1 font-medium">Day 2 and Day 3 are locked until Day 1 is
                        completed for this student.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Methodology
                        Clinical Scenario</label>
                    <select id="studentInputScenario"
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all bg-white">
                        <option value="START_NODE" data-original-text="Scenario A: Mass Casualty (Trauma/Respiratory)"
                            data-day="1">
                            Scenario A: Mass Casualty (Trauma/Respiratory)</option>
                        <option value="SCENARIO_B_START"
                            data-original-text="Scenario B: Structural Fire (Ambulatory Check)" data-day="2">Scenario B:
                            Structural
                            Fire (Ambulatory Check)</option>
                        <option value="SCENARIO_C_START"
                            data-original-text="Scenario C: Vehicle Collision (No Respiration)" data-day="2">Scenario C:
                            Vehicle
                            Collision (No Respiration)</option>
                        <option value="SCENARIO_D_START"
                            data-original-text="Scenario D: Factory Explosion (RPM Sequence)" data-day="2">Scenario D:
                            Factory
                            Explosion (RPM Sequence)</option>
                        <option value="SCENARIO_E_START"
                            data-original-text="Scenario E: The Structural Collapse (Post-Test)" data-day="3">Scenario
                            E: The
                            Structural Collapse (Post-Test)</option>
                    </select>
                </div>

                <div id="statusAlertBox"
                    class="hidden p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-start gap-2.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span id="statusAlertText"></span>
                </div>

                <button onclick="startStudentMode()"
                    class="w-full bg-brand-accent hover:bg-brand-tealDark text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-brand-accent/20 transition-all">
                    <span>Launch Simulator</span>
                    <i data-lucide="play" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </main>

    <main id="loginView" class="view-frame w-full max-w-md">
        <button onclick="showView('homeView')"
            class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Main Menu
        </button>

        <div class="premium-card overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold leading-tight">Instructor Login</h2>
                    <p class="text-xs text-brand-mintLight">Access administrative research metrics</p>
                </div>
            </div>

            <div class="p-6 space-y-5 bg-white">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Academic
                        Email</label>
                    <input type="email" id="loginEmail"
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all"
                        value="p.williams@university.edu">
                </div>
                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Password</label>
                    <input type="password" id="loginPassword"
                        class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all"
                        value="password123">
                </div>

                <button onclick="executeLogin()"
                    class="w-full bg-brand-teal hover:bg-brand-tealDark text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-brand-teal/20 transition-all">
                    <span>Authenticate Account</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <div class="p-4 bg-brand-bg border border-brand-mint rounded-xl space-y-2">
                    <span class="block text-xs font-bold text-brand-tealDark uppercase tracking-wider">Demo Access
                        Profiles</span>
                    <div class="text-xs space-y-1 text-brand-dark">
                        <p class="font-semibold">p.williams@university.edu <span
                                class="font-normal text-brand-muted">(Dr. Patricia Williams)</span></p>
                        <p class="font-semibold">r.kim@university.edu <span class="font-normal text-brand-muted">(Prof.
                                Robert Kim)</span></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <main id="studentView" class="view-frame w-full max-w-5xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-brand-dark flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-brand-teal animate-pulse"></span>
                    Clinical Simulation Terminal
                </h1>
                <p class="text-sm text-brand-muted">Execute optimal protocols rapidly to optimize Path Efficiency.</p>
            </div>
            <button onclick="confirmExitSimulation()"
                class="self-start md:self-auto px-4 py-2 border-2 border-brand-mint bg-white text-brand-tealDark font-bold rounded-xl hover:bg-brand-mintLight transition-colors flex items-center gap-2 text-sm">
                <i data-lucide="log-out" class="w-4 h-4"></i> Exit Scenario
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="premium-card p-6 bg-white space-y-6">
                    <div class="flex items-center justify-between border-b border-brand-bg pb-4">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="clipboard-list" class="text-brand-teal w-5 h-5"></i>
                            <span id="scenarioTitle" class="font-bold text-brand-dark">Active Case: Disaster Area
                                Alpha</span>
                        </div>
                        <span
                            class="px-3 py-1 bg-brand-mintLight text-brand-tealDark font-bold text-xs rounded-full uppercase tracking-wider">START
                            Protocol</span>
                    </div>

                    <div class="space-y-4">
                        <span class="text-xs font-bold text-brand-tealDark uppercase tracking-wider block">Clinical
                            Presentation / Prompt</span>
                        <div id="nodePrompt"
                            class="p-5 bg-brand-bg rounded-xl text-brand-dark font-medium border-l-4 border-brand-teal text-base leading-relaxed">
                            Loading triage assessment data...
                        </div>
                    </div>

                    <div class="space-y-4">
                        <span class="text-xs font-bold text-brand-tealDark uppercase tracking-wider block">Determine
                            Next Step</span>
                        <div id="optionsWrapper" class="flex flex-col gap-3"></div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="premium-card p-6 bg-white space-y-5">
                    <h3 class="text-md font-bold text-brand-dark border-b border-brand-bg pb-3 flex items-center gap-2">
                        <i data-lucide="gauge" class="w-5 h-5 text-brand-teal"></i> Active Case Telemetry
                    </h3>

                    <div class="space-y-3.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Participant Name:</span>
                            <span id="telemetryName"
                                class="font-bold text-brand-dark text-xs truncate max-w-[120px] text-right">Live
                                Participant</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Clinical FSM Node:</span>
                            <span id="telemetryState"
                                class="font-mono text-brand-teal font-bold bg-brand-bg px-2.5 py-0.5 rounded-lg text-xs">START_NODE</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Decision Latency (L):</span>
                            <span id="telemetryLatency" class="font-bold text-red-500 font-mono text-base">0.00s</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Protocol Deviations (E-Penalty):</span>
                            <span id="telemetryDeviations"
                                class="font-bold text-brand-dark bg-brand-bg w-7 h-7 flex items-center justify-center rounded-full text-xs">0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Path Efficiency (E):</span>
                            <span id="telemetryEfficiency" class="font-bold text-brand-accent text-base">100%</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-brand-bg text-[11px] text-brand-muted leading-relaxed">
                        <p class="font-semibold text-brand-dark mb-1">How telemetry is scored:</p>
                        Decision Latency (L) is tracked continuously. Taking incorrect paths (deviations) penalizes
                        overall Path Efficiency (E) instantly.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <main id="dashboardView" class="view-frame w-full max-w-6xl">
        <div class="premium-card p-5 bg-white mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-teal rounded-full flex items-center justify-center text-white">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="font-bold text-brand-dark">Performance Analytics Panel</h2>
                    <span class="text-xs text-brand-muted">Active Research Cohort: Sections Combined</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="exportToCSV()"
                    class="px-4 py-2 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl text-sm transition-colors flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Export to Excel/SPSS
                </button>
                <div class="text-right hidden md:block border-l border-brand-mint pl-4 ml-2">
                    <p class="text-sm font-bold text-brand-dark">Dr. Patricia Williams</p>
                    <p class="text-xs text-brand-muted">Emergency Dept Advisor</p>
                </div>
                <button onclick="showView('homeView')"
                    class="px-4 py-2 bg-brand-bg hover:bg-brand-mint text-brand-tealDark font-bold rounded-xl text-sm transition-colors flex items-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
            </div>
        </div>

        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-brand-dark">Performance Dashboard</h1>
            <p class="text-sm text-brand-muted">Aggregate analytics computed from branching FSM state logs</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="users" class="w-4 h-4 text-brand-teal"></i> Total Participants
                </div>
                <div>
                    <div id="dash-total-students" class="text-3xl font-black text-brand-dark leading-tight mt-2">0</div>
                    <span class="text-[11px] text-brand-muted font-medium">Recorded live submissions</span>
                </div>
            </div>

            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="hourglass" class="w-4 h-4 text-brand-teal"></i> Avg Decision Latency (L)
                </div>
                <div>
                    <div id="dash-avg-latency"
                        class="text-3xl font-black text-brand-dark leading-tight mt-2 text-red-500">0s</div>
                    <span class="text-[11px] text-brand-muted font-medium">Standard baseline threshold is &lt; 5s</span>
                </div>
            </div>

            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="award" class="w-4 h-4 text-brand-teal"></i> Mean Path Efficiency (E)
                </div>
                <div>
                    <div id="dash-avg-efficiency"
                        class="text-3xl font-black text-brand-dark leading-tight mt-2 text-brand-accent">0%</div>
                    <span class="text-[11px] text-brand-muted font-medium">Ratio of actual to optimal steps</span>
                </div>
            </div>

            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="shield" class="w-4 h-4 text-brand-teal"></i> Diagnostic Accuracy
                </div>
                <div>
                    <div id="dash-avg-accuracy"
                        class="text-3xl font-black text-brand-dark leading-tight mt-2 text-brand-teal">0%</div>
                    <span class="text-[11px] text-brand-muted font-medium">Optimal first-try accuracy</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="premium-card p-6 bg-white lg:col-span-2 space-y-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                        <i data-lucide="flame" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark">Clinical Decision Cognitive Heatmap</h3>
                        <p class="text-xs text-brand-muted">Tracks hesitation metrics and cognitive friction points</p>
                    </div>
                </div>
                <div class="space-y-4 pt-2" id="heatmapContainer"></div>
            </div>

            <div class="premium-card p-6 bg-white space-y-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i data-lucide="trophy" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark">Student Performance Ranking</h3>
                        <p class="text-xs text-brand-muted">Sort of top paths (Efficiency &amp; Speed)</p>
                    </div>
                </div>
                <div id="dash-ranking-list" class="space-y-3.5"></div>
            </div>
        </div>

        <div class="premium-card p-6 bg-white mb-8">

            <div class="flex flex-wrap gap-4 items-center justify-between mb-5">
                <div class="relative flex-1 min-w-[280px]">
                    <i data-lucide="search"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-muted"></i>
                    <input type="text" id="studentSearch" placeholder="Search student name or ID..."
                        class="w-full pl-11 pr-4 py-3 bg-brand-bg border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all placeholder-brand-muted/70">
                </div>

                <button type="button" id="toggleFilterBtn" onclick="toggleFilterPanel()"
                    class="flex items-center gap-2 px-5 py-3 bg-white border-2 border-brand-mint rounded-xl text-brand-tealDark font-bold hover:bg-brand-mintLight transition-colors">
                    <i data-lucide="settings-2" class="w-4 h-4"></i> Advanced Filters
                    <i data-lucide="chevron-down" id="arrowIcon"
                        class="w-4 h-4 transition-transform duration-200 ml-1"></i>
                </button>
            </div>

            <div id="filterPanel" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out mb-0">
                <div
                    class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5 bg-brand-bg border-2 border-brand-mint rounded-xl mb-5">
                    <div>
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-brand-tealDark mb-2">Simulation
                            Target</label>
                        <select id="filterScenario"
                            class="w-full px-4 py-2.5 bg-white border-2 border-brand-mint rounded-lg text-brand-dark font-medium focus:border-brand-teal outline-none cursor-pointer">
                            <option value="all">✨ All Scenarios</option>
                            <option value="Scenario A">📅 Scenario A (Day 1)</option>
                            <option value="Scenario B">📅 Scenario B (Day 2)</option>
                            <option value="Scenario C">📅 Scenario C (Day 2)</option>
                            <option value="Scenario D">📅 Scenario D (Day 2)</option>
                            <option value="Scenario E">📅 Scenario E (Day 3)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-tealDark mb-2">Sort
                            Records By</label>
                        <select id="sortRecords"
                            class="w-full px-4 py-2.5 bg-white border-2 border-brand-mint rounded-lg text-brand-dark font-medium focus:border-brand-teal outline-none cursor-pointer">
                            <option value="default">🕒 Date (Newest First)</option>
                            <option value="name-asc">🔤 Alphabetical (A - Z)</option>
                            <option value="name-desc">🔤 Alphabetical (Z - A)</option>
                            <option value="time-fastest">⚡ Response Time (Fastest)</option>
                            <option value="time-slowest">🐢 Response Time (Slowest)</option>
                            <option value="accuracy-highest">🎯 Performance Accuracy (Highest)</option>
                            <option value="sus-highest">📊 SUS Score (Highest)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border-2 border-brand-mint">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-brand-mintLight border-b-2 border-brand-mint text-brand-tealDark font-bold">
                            <th class="p-4">Student ID</th>
                            <th class="p-4">Full Name</th>
                            <th class="p-4">Cohort</th>
                            <th class="p-4">Scenario Matrix</th>
                            <th class="p-4">Latency</th>
                            <th class="p-4">Accuracy</th>
                            <th class="p-4 text-right">SUS Rating</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody" class="divide-y divide-brand-mint bg-white">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script id="laravel-student-data" type="application/json">
        {!! json_encode($studentRecords ?? []) !!}
    </script>

    <script>
        // Finite State Machine (FSM) Scenarios and Clinical Routing Maps
        const triageFSM = {
            START_NODE: {
                prompt: "Emergency Triage Area: You encounter a patient on-scene presenting with a compound distal leg fracture. Massive bleeding is controlled via local compression bandage. What is your next protocol investigation stage?",
                options: [
                    { text: "Assess the patient's spontaneous respiratory status", nextState: "CHECK_RESPIRATIONS", optimal: true },
                    { text: "Measure peripheral perfusion via capillary refill time", nextState: "CHECK_PERFUSION", optimal: false },
                    { text: "Request the patient follow basic command signals ('Squeeze my hand')", nextState: "CHECK_MENTAL", optimal: false }
                ]
            },
            CHECK_RESPIRATIONS: {
                prompt: "You lean in close to evaluate respiratory movement. The patient is breathing spontaneously, but rapid tachypnea is noted, at 34 breaths per minute. Applying the START standard, what is the triage action?",
                options: [
                    { text: "Immediately tag patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED", optimal: true },
                    { text: "Ignore the breathing rate and check peripheral pulse/capillary refill", nextState: "CHECK_PERFUSION", optimal: false },
                    { text: "Assess cognitive response by questioning standard commands", nextState: "CHECK_MENTAL", optimal: false }
                ]
            },
            CHECK_PERFUSION: {
                prompt: "You palpate the distal radial pulse and assess capillary refill. Radial pulse is extremely thready. CRT is measured at 3.6 seconds (Standard START ceiling is 2s). What is the appropriate triage outcome?",
                options: [
                    { text: "Immediately classify patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED", optimal: true },
                    { text: "Execute cognitive command assessments to confirm mental state", nextState: "CHECK_MENTAL", optimal: false }
                ]
            },
            CHECK_MENTAL: {
                prompt: "The patient can look in your direction but presents severe cognitive disorientation, failing to follow hand squeezing instructions. What is the appropriate triage designation?",
                options: [
                    { text: "Classify patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED", optimal: true },
                    { text: "Classify patient as DELAYED (YELLOW TAG)", nextState: "RESULT_YELLOW", optimal: false }
                ]
            },
            RESULT_RED: {
                isTerminal: true,
                tagColor: "bg-triage-red",
                textColor: "text-red-100",
                tagName: "Immediate (Red Tag)",
                prompt: "Assessment Complete: Based on START triage rules, this patient exhibits severe respiratory/perfusion/mental status disruption, warranting priority care. Your diagnostic pathway is clinically accurate!"
            },
            RESULT_YELLOW: {
                isTerminal: true,
                tagColor: "bg-triage-yellow",
                textColor: "text-yellow-900",
                tagName: "Delayed (Yellow Tag)",
                prompt: "Assessment Complete: However, your final classification is sub-optimal. A patient showing respiratory rate >30/min, poor perfusion, or mental confusion cannot be tagged yellow. They require an immediate RED tag."
            },

            // --- SCENARIO B ---
            SCENARIO_B_START: {
                prompt: "Emergency Triage Area (Scenario B): A structural fire has occurred. A patient approaches you walking, coughing slightly, complaining of a burned arm. What is the immediate START protocol action?",
                options: [
                    { text: "Direct the patient to the designated 'Walking Wounded' collection area", nextState: "RESULT_GREEN", optimal: true },
                    { text: "Assess respiratory rate immediately to ensure airway isn't compromised", nextState: "SCENARIO_B_WRONG", optimal: false },
                    { text: "Check capillary refill time on the unburned arm", nextState: "SCENARIO_B_WRONG", optimal: false }
                ]
            },
            RESULT_GREEN: {
                isTerminal: true,
                tagColor: "bg-triage-green",
                textColor: "text-green-100",
                tagName: "Minor (Green Tag)",
                prompt: "Assessment Complete: By confirming the patient can walk, START protocol dictates immediate classification as MINOR. They are grouped into the 'walking wounded' before further checks."
            },
            SCENARIO_B_WRONG: {
                isTerminal: true,
                tagColor: "bg-triage-black",
                textColor: "text-red-200",
                tagName: "Incorrect Flow Logic",
                prompt: "Assessment Complete: Protocol breakdown. In START triage, if a patient is ambulatory (able to walk), they are immediately tagged GREEN without proceeding down the RPM checklist."
            },

            // --- NEW: SCENARIO C (Day 2) ---
            SCENARIO_C_START: {
                prompt: "Emergency Triage Area (Scenario C): Vehicle Collision. You find an adult patient ejected from the vehicle. They are completely unresponsive, apneic, and pulseless.",
                options: [
                    { text: "Immediately tag patient as DECEASED (BLACK TAG)", nextState: "SCENARIO_C_WRONG", optimal: false },
                    { text: "Perform manual airway maneuver (jaw-thrust/head-tilt) to check for spontaneous breath", nextState: "SCENARIO_C_AIRWAY", optimal: true },
                    { text: "Check capillary refill time", nextState: "SCENARIO_C_WRONG", optimal: false }
                ]
            },
            SCENARIO_C_AIRWAY: {
                prompt: "You open the airway manually. The patient remains completely apneic (no spontaneous breathing). What is the protocol action?",
                options: [
                    { text: "Tag patient as DECEASED (BLACK TAG)", nextState: "RESULT_BLACK", optimal: true },
                    { text: "Tag patient as IMMEDIATE (RED TAG)", nextState: "SCENARIO_C_WRONG_2", optimal: false }
                ]
            },
            RESULT_BLACK: {
                isTerminal: true,
                tagColor: "bg-slate-800",
                textColor: "text-slate-100",
                tagName: "Deceased (Black Tag)",
                prompt: "Assessment Complete: Based on START protocol, an apneic patient who does not breathe after opening the airway is tagged Deceased to conserve resources."
            },
            SCENARIO_C_WRONG: {
                isTerminal: true,
                tagColor: "bg-triage-red",
                textColor: "text-red-100",
                tagName: "Critical Error (Skipped Airway Check)",
                prompt: "Assessment Complete: You must ALWAYS attempt to open the airway before determining a patient is deceased."
            },
            SCENARIO_C_WRONG_2: {
                isTerminal: true,
                tagColor: "bg-triage-red",
                textColor: "text-red-100",
                tagName: "Critical Error (Red Tagged a Deceased Patient)",
                prompt: "Assessment Complete: A patient who does not breathe even after airway repositioning cannot be tagged Red. They must be tagged Black."
            },

            SCENARIO_D_START: {
                prompt: "Emergency Triage Area (Scenario D): Factory Explosion. A patient is on the ground with bilateral leg fractures and is completely unable to stand or walk. What is your immediate next step?",
                options: [
                    { text: "Assess the patient's spontaneous respiratory status", nextState: "SCENARIO_D_RESP", optimal: true },
                    { text: "Check capillary refill time", nextState: "SCENARIO_D_WRONG", optimal: false },
                    { text: "Tag as DELAYED (YELLOW TAG) immediately since they cannot walk", nextState: "SCENARIO_D_WRONG_2", optimal: false }
                ]
            },
            SCENARIO_D_RESP: {
                prompt: "Respirations are steady at 18 breaths per minute (Normal is < 30). What is your next protocol investigation stage?",
                options: [
                    { text: "Measure peripheral perfusion via capillary refill time", nextState: "SCENARIO_D_PERF", optimal: true },
                    { text: "Request the patient follow basic command signals", nextState: "SCENARIO_D_WRONG", optimal: false },
                    { text: "Tag as MINOR (GREEN TAG)", nextState: "SCENARIO_D_WRONG_2", optimal: false }
                ]
            },
            SCENARIO_D_PERF: {
                prompt: "Radial pulse is present and strong. Capillary refill time (CRT) is evaluated at 1.5 seconds. What is the final step in the START protocol?",
                options: [
                    { text: "Assess cognitive/mental status (e.g., 'Squeeze my hand')", nextState: "SCENARIO_D_MENTAL", optimal: true },
                    { text: "Tag as IMMEDIATE (RED TAG)", nextState: "SCENARIO_D_WRONG_2", optimal: false }
                ]
            },
            SCENARIO_D_MENTAL: {
                prompt: "The patient is alert, oriented, and successfully follows your command to squeeze your hands. What is the appropriate triage designation?",
                options: [
                    { text: "Classify patient as DELAYED (YELLOW TAG)", nextState: "RESULT_YELLOW_D", optimal: true },
                    { text: "Classify patient as MINOR (GREEN TAG)", nextState: "SCENARIO_D_WRONG_2", optimal: false }
                ]
            },
            RESULT_YELLOW_D: {
                isTerminal: true,
                tagColor: "bg-triage-yellow",
                textColor: "text-yellow-900",
                tagName: "Delayed (Yellow Tag)",
                prompt: "Assessment Complete: Correct path! The patient cannot walk, but cleared all RPM (Respirations, Perfusion, Mental Status) benchmarks. They require a Yellow Tag."
            },
            SCENARIO_D_WRONG: {
                isTerminal: true,
                tagColor: "bg-triage-black",
                textColor: "text-red-200",
                tagName: "Sequence Error",
                prompt: "Assessment Complete: Protocol breakdown. You must follow the RPM sequence exactly. Skipping steps risks missing hidden shock indicators."
            },
            SCENARIO_D_WRONG_2: {
                isTerminal: true,
                tagColor: "bg-triage-black",
                textColor: "text-red-200",
                tagName: "Misclassification Error",
                prompt: "Assessment Complete: Incorrect Tag. You failed to apply the START criteria correctly based on the current indicators."
            },

            SCENARIO_E_START: {
                prompt: "The Structural Collapse (Scenario E): You find a patient trapped under heavy concrete debris. They have sustained a massive crush injury to the pelvis. Bystanders have applied a tourniquet. What is your next protocol investigation stage?",
                options: [
                    { text: "Assess the patient's spontaneous respiratory status", nextState: "SCENARIO_E_RESPIRATIONS", optimal: true },
                    { text: "Measure peripheral perfusion via capillary refill time", nextState: "SCENARIO_E_PERFUSION", optimal: false },
                    { text: "Request the patient follow basic command signals ('Squeeze my hand')", nextState: "SCENARIO_E_MENTAL", optimal: false }
                ]
            },
            SCENARIO_E_RESPIRATIONS: {
                prompt: "You clear concrete dust away to evaluate breathing. The patient is gasping aggressively, with a respiratory rate of 38 breaths per minute. Applying the START standard, what is the triage action?",
                options: [
                    { text: "Immediately tag patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED_E", optimal: true },
                    { text: "Ignore the breathing rate and check peripheral pulse/capillary refill", nextState: "SCENARIO_E_PERFUSION", optimal: false },
                    { text: "Assess cognitive response by questioning standard commands", nextState: "SCENARIO_E_MENTAL", optimal: false }
                ]
            },
            SCENARIO_E_PERFUSION: {
                prompt: "You bypass respirations and assess capillary refill on an exposed hand. CRT is measured at 4.2 seconds (severe delay). What is the appropriate triage outcome?",
                options: [
                    { text: "Immediately classify patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED_E", optimal: true },
                    { text: "Execute cognitive command assessments to confirm mental state", nextState: "SCENARIO_E_MENTAL", optimal: false }
                ]
            },
            SCENARIO_E_MENTAL: {
                prompt: "The patient's eyes are open but they are completely unresponsive to your commands to move or blink intentionally. What is the appropriate triage designation?",
                options: [
                    { text: "Classify patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED_E", optimal: true },
                    { text: "Classify patient as DELAYED (YELLOW TAG)", nextState: "RESULT_YELLOW_E", optimal: false }
                ]
            },
            RESULT_RED_E: {
                isTerminal: true,
                tagColor: "bg-triage-red",
                textColor: "text-red-100",
                tagName: "Immediate (Red Tag)",
                prompt: "Assessment Complete: Correct! Just like Day 1, severe tachypnea (>30/min) dictates an immediate RED tag. Your diagnostic pathway is clinically accurate!"
            },
            RESULT_YELLOW_E: {
                isTerminal: true,
                tagColor: "bg-triage-yellow",
                textColor: "text-yellow-900",
                tagName: "Delayed (Yellow Tag)",
                prompt: "Assessment Complete: Sub-optimal classification. A patient showing a respiratory rate >30/min, poor perfusion, or mental confusion cannot be tagged yellow. They require an immediate RED tag."
            }
        };

        const susQuestions = [
            "I think that I would like to use this triage system frequently.",
            "I found the branching simulation engine unnecessarily complex.",
            "I thought the interface layouts were easy to interact with.",
            "I think that I would need the support of a technical specialist to use this platform.",
            "I found the various configuration elements were well integrated.",
            "I thought there was too much structural inconsistency across the scenarios.",
            "I imagine that most healthcare peers would learn to use this application rapidly.",
            "I found the system tracking navigation very cumbersome to execute.",
            "I felt highly confident navigating between the scenario decision tracks.",
            "I needed to learn many operational steps before I could execute simulated triage cases."
        ];

        function getInitialRecords() {
            try {
                const rawData = document.getElementById('laravel-student-data').textContent.trim();
                if (rawData.includes('{!!') || rawData.includes('json_encode') || rawData === '') {
                    return [];
                }
                return JSON.parse(rawData);
            } catch (e) {
                return [];
            }
        }

        const appState = {
            studentRecords: getInitialRecords(),
            hesitationCount: { node_1: 12, node_2: 21, node_3: 8 }
        };

        let currentState = 'START_NODE';
        let nodeDisplayTime = 0;
        let latencyTimer = null;
        let deviations = 0;
        let answerHistory = [];
        let currentSession = {};
        let shouldLockAfterReview = false;

        let isFinalDay = false;

        function initSUSForm() {
            const wrapper = document.getElementById('susQuestionsWrapper');
            wrapper.innerHTML = '';
            susQuestions.forEach((q, index) => {
                const qNum = index + 1;
                wrapper.innerHTML += `
                    <div class="pt-3 first:pt-0">
                        <p class="text-sm font-semibold text-brand-dark mb-2"><span class="text-brand-teal">${qNum}.</span> ${q}</p>
                        <div class="flex justify-between gap-2">
                            ${[1, 2, 3, 4, 5].map(val => `
                                <label class="flex-1 text-center cursor-pointer">
                                    <input type="radio" name="sus_q_${qNum}" value="${val}" class="peer sr-only">
                                    <div class="py-2 border border-brand-mint rounded peer-checked:bg-brand-teal peer-checked:text-white hover:bg-brand-mintLight transition-colors text-xs text-brand-dark font-medium">
                                        ${val}
                                    </div>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                `;
            });
        }
        initSUSForm();

        function showView(viewId) {
            document.querySelectorAll('.view-frame').forEach(view => view.classList.remove('active'));
            const activeView = document.getElementById(viewId);
            if (activeView) activeView.classList.add('active');
            lucide.createIcons();
        }

        function toggleConsent(checkbox) {
            const btn = document.getElementById('btnAcceptConsent');
            if (checkbox.checked) {
                btn.disabled = false;
                btn.classList.remove('bg-slate-300', 'text-slate-500', 'cursor-not-allowed');
                btn.classList.add('bg-brand-teal', 'text-white', 'hover:bg-brand-tealDark');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-slate-300', 'text-slate-500', 'cursor-not-allowed');
                btn.classList.remove('bg-brand-teal', 'text-white', 'hover:bg-brand-tealDark');
            }
        }

        function acceptConsentForm() {
            document.getElementById('consentCheckbox').checked = false;
            toggleConsent(document.getElementById('consentCheckbox'));
            showView('studentSetupView');
        }

        function executeLogin() {
            const email = document.getElementById('loginEmail').value.trim();
            if (email) {
                refreshDashboard();
                showView('dashboardView');
            }
        }

        function toggleHelpModal() {
            document.getElementById('helpModal').classList.toggle('hidden');
        }

        function confirmExitSimulation() {
            document.getElementById('exitConfirmModal').classList.remove('hidden');
        }

        function closeExitModal() {
            document.getElementById('exitConfirmModal').classList.add('hidden');
        }

        function executeExitSimulation() {
            if (latencyTimer) clearInterval(latencyTimer);
            closeExitModal();
            showView('homeView');
        }

        function openSUSModal() {
            document.getElementById('susModal').classList.remove('hidden');
        }

        function closeResultAndOpenSUS() {
            document.getElementById('resultModal').classList.add('hidden');
            openSUSModal();
        }

        function exportToCSV() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Date Taken,Student ID,Name,Cohort,Scenario,Decision Latency (s),Path Efficiency (%),Accuracy (%),SUS Final Score,SUS Q1,SUS Q2,SUS Q3,SUS Q4,SUS Q5,SUS Q6,SUS Q7,SUS Q8,SUS Q9,SUS Q10\n";

            appState.studentRecords.forEach(r => {
                let dateStr = r.created_at ? new Date(r.created_at).toLocaleDateString() : new Date().toLocaleDateString();
                let id = r.student_id || r.id || 'N/A';
                let name = r.student_name || r.name || 'Unknown';
                let scenario = r.scenario || 'Scenario A';
                let sus = r.sus_score || 'N/A';

                let qResponses = Array(10).fill('N/A');
                if (r.sus_responses) {
                    try {
                        let parsed = typeof r.sus_responses === 'string' ? JSON.parse(r.sus_responses) : r.sus_responses;
                        for(let i=1; i<=10; i++) {
                            qResponses[i-1] = parsed[`q${i}`] || 'N/A';
                        }
                    } catch(e) {}
                }

                let row = `${dateStr},${id},"${name}","${r.cohort}","${scenario}",${r.latency},${r.efficiency},${r.accuracy},${sus},${qResponses.join(',')}`;
                csvContent += row + "\n";
            });
            var link = document.createElement("a");
            link.setAttribute("href", encodeURI(csvContent));
            link.setAttribute("download", "TriageSim_Methodology_Export.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

// Global tracker to chain sequential Day 2 states smoothly
        window.nextScenarioChain = null;
        window.savedStudentData = null;

        function checkStudentHistory(inputField) {
            const id = inputField.value.trim();
            if (!id) return;

            const daySelect = document.getElementById('studentInputDay');
            const scenarioSelect = document.getElementById('studentInputScenario');
            if (!scenarioSelect) return;
            const scenarioOptions = scenarioSelect.options;

            const pastRecords = appState.studentRecords.filter(r => (r.student_id === id || r.id === id));
            const completedScenarios = pastRecords.map(r => r.scenario ? r.scenario.split(' (')[0].split(':')[0].trim() : '');

            const hasCompletedDay1 = completedScenarios.includes("Scenario A");
            const hasCompletedB = completedScenarios.includes("Scenario B");
            const hasCompletedC = completedScenarios.includes("Scenario C");
            const hasCompletedD = completedScenarios.includes("Scenario D");

            const hasCompletedAllDay2 = hasCompletedB && hasCompletedC && hasCompletedD;

            // Save student data context in case they use sequential button chaining
            if (pastRecords.length > 0) {
                window.savedStudentData = {
                    id: id,
                    name: pastRecords[0].student_name || pastRecords[0].name,
                    cohort: pastRecords[0].cohort
                };
            }

            // 1. Label completed modules with checkmarks
            for (let i = 0; i < scenarioOptions.length; i++) {
                const originalText = scenarioOptions[i].getAttribute('data-original-text') || scenarioOptions[i].text;
                if (!scenarioOptions[i].getAttribute('data-original-text')) {
                    scenarioOptions[i].setAttribute('data-original-text', originalText);
                }

                const shortTitle = originalText.split(' (')[0].split(':')[0].trim();
                if (completedScenarios.includes(shortTitle)) {
                    scenarioOptions[i].text = originalText.split(' - ✓')[0] + " - ✓ (COMPLETED)";
                } else {
                    scenarioOptions[i].text = originalText;
                }
            }

            // 2. Strict Sequential Progression Gatekeeping
            if (daySelect) {
                const opt1 = daySelect.querySelector('option[value="1"]');
                const opt2 = daySelect.querySelector('option[value="2"]');
                const opt3 = daySelect.querySelector('option[value="3"]');

                if (opt1) opt1.disabled = true;
                if (opt2) opt2.disabled = true;
                if (opt3) opt3.disabled = true;

                if (!hasCompletedDay1) {
                    if (opt1) opt1.disabled = false;
                    daySelect.value = '1';
                } else if (!hasCompletedAllDay2) {
                    if (opt2) opt2.disabled = false;
                    daySelect.value = '2';
                } else {
                    if (opt3) opt3.disabled = false;
                    daySelect.value = '3';
                }
            }

            // Cache progression checkpoints globally to let the option filter hide things accurately
            window.day2Progression = { hasCompletedB, hasCompletedC, hasCompletedD };

            updateScenarioOptionsByDay();
        }

        function updateScenarioOptionsByDay() {
            const daySelect = document.getElementById('studentInputDay');
            const scenarioSelect = document.getElementById('studentInputScenario');
            if (!daySelect || !scenarioSelect) return;

            const selectedDay = parseInt(daySelect.value, 10);
            const prog = window.day2Progression || { hasCompletedB: false, hasCompletedC: false, hasCompletedD: false };
            let firstSelectableIndex = -1;

            for (let i = 0; i < scenarioSelect.options.length; i++) {
                const option = scenarioSelect.options[i];
                const optionDay = parseInt(option.getAttribute('data-day'), 10);
                const shortTitle = option.getAttribute('data-original-text').split(' (')[0].split(':')[0].trim();
                const isCompleted = option.text.includes('✓ (COMPLETED)');

                // Hide scenarios not belonging to current day configuration
                if (optionDay !== selectedDay) {
                    option.hidden = true;
                    option.disabled = true;
                    option.style.display = 'none';
                    continue;
                }

                // Default state for visible options
                option.hidden = false;
                option.style.display = 'block';
                option.disabled = isCompleted;

                // --- DAY 2 LINEAR UNLOCK FLOW (B -> C -> D) ---
                if (selectedDay === 2) {
                    if (shortTitle === "Scenario B" && isCompleted) {
                        option.disabled = true;
                    }
                    else if (shortTitle === "Scenario C" && !prog.hasCompletedB) {
                        option.disabled = true; // Hidden/Locked until B is completely out of the way
                    }
                    else if (shortTitle === "Scenario D" && (!prog.hasCompletedB || !prog.hasCompletedC)) {
                        option.disabled = true; // Hidden/Locked until C is completely out of the way
                    }
                }

                if (!option.disabled && firstSelectableIndex === -1) {
                    firstSelectableIndex = i;
                }
            }

            if (scenarioSelect.selectedIndex < 0 ||
                scenarioSelect.options[scenarioSelect.selectedIndex].hidden ||
                scenarioSelect.options[scenarioSelect.selectedIndex].disabled) {
                scenarioSelect.selectedIndex = firstSelectableIndex;
            }
        }

        function dispatchSessionPayload(payload, successMessage) {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');

            document.getElementById('resultModal').classList.add('hidden');
            document.getElementById('susModal').classList.add('hidden');

            // Calculate sequence chaining pathways for Day 2 linear routing
            window.nextScenarioChain = null;
            if (payload.scenario.includes("Scenario B")) {
                window.nextScenarioChain = { value: "SCENARIO_C_START", name: "Scenario C" };
            } else if (payload.scenario.includes("Scenario C")) {
                window.nextScenarioChain = { value: "SCENARIO_D_START", name: "Scenario D" };
            }

            if (csrfMeta && csrfMeta.getAttribute('content')) {
                fetch('/api/sessions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    appState.studentRecords.push(payload);
                    refreshDashboard();
                    showSuccessModal(successMessage);
                })
                .catch(err => console.error("Database sync exception: ", err));
            } else {
                appState.studentRecords.push(payload);
                refreshDashboard();
                showSuccessModal(successMessage + " (Saved Locally)");
            }
        }

        function showSuccessModal(message) {
            document.getElementById('successModalMessage').innerText = message;
            const actionContainer = document.getElementById('successModalActions');
            actionContainer.innerHTML = ''; // Clear prior elements

            if (window.nextScenarioChain) {
                // Change prompt text to ask them if they want to move to the next item
                document.getElementById('successModalMessage').innerText =
                    message + ` Would you like to proceed immediately to ${window.nextScenarioChain.name}?`;

                // Add the immediate chain progression action item
                const proceedBtn = document.createElement('button');
                proceedBtn.className = "w-full py-3 px-4 bg-brand-teal hover:bg-brand-tealDark text-white font-bold rounded-xl transition-all shadow-lg shadow-brand-teal/20 mb-2";
                proceedBtn.innerText = `Yes, Proceed to ${window.nextScenarioChain.name}`;
                proceedBtn.onclick = function() {
                    executeImmediateChainRoute();
                };
                actionContainer.appendChild(proceedBtn);

                // Add the normal back-out option
                const cancelBtn = document.createElement('button');
                cancelBtn.className = "w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 text-brand-dark font-medium rounded-xl transition-all text-sm";
                cancelBtn.innerText = "No, Return to Main Menu";
                cancelBtn.onclick = function() {
                    closeSuccessModal();
                };
                actionContainer.appendChild(cancelBtn);
            } else {
                // Standard closure layout button setup
                const defaultBtn = document.createElement('button');
                defaultBtn.className = "w-full py-3 px-4 bg-brand-teal hover:bg-brand-tealDark text-white font-bold rounded-xl transition-all shadow-lg shadow-brand-teal/20";
                defaultBtn.innerText = "Return to Main Menu";
                defaultBtn.onclick = function() {
                    closeSuccessModal();
                };
                actionContainer.appendChild(defaultBtn);
            }

            document.getElementById('successModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function executeImmediateChainRoute() {
            if (!window.nextScenarioChain || !window.savedStudentData) {
                closeSuccessModal();
                return;
            }

            const targetChain = window.nextScenarioChain;
            const profile = window.savedStudentData;

            // Shut down modal overlay elements safely
            document.getElementById('successModal').classList.add('hidden');
            window.nextScenarioChain = null;

            // Direct internal boot initialization tracking sequence
            currentState = targetChain.value;
            deviations = 0;
            answerHistory = [];

            currentSession = {
                id: profile.id,
                name: profile.name,
                cohort: profile.cohort,
                scenarioName: targetChain.name,
                startState: targetChain.value,
                cumulativeLatency: 0, steps: 0, optimalSteps: 0,
                finalLatency: 0, finalEfficiency: 0, finalAccuracy: 0,
                attemptsRemaining: 3
            };

            document.getElementById('telemetryName').innerText = currentSession.name;
            document.getElementById('scenarioTitle').innerText = `Active Case: ${currentSession.scenarioName}`;
            document.getElementById('telemetryEfficiency').innerText = "100%";
            document.getElementById('telemetryDeviations').innerText = "0";

            // Open screen views directly bypassing intermediate setup procedures
            showView('studentView');
            renderFSMNode();
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            window.nextScenarioChain = null;

            // Re-trigger login evaluation check to update table view selections dynamically
            const idInput = document.getElementById('studentInputId');
            if (idInput && idInput.value.trim()) {
                checkStudentHistory(idInput);
            }

            showView('homeView');
        }

        function startStudentMode() {
    const inputName = document.getElementById('studentInputName').value.trim();
    const inputId = document.getElementById('studentInputId').value.trim();
    const alertBox = document.getElementById('statusAlertBox');
    const alertText = document.getElementById('statusAlertText');

    if (!inputName || !inputId) {
        alertText.innerText = "Error: Student Name and ID are strictly required to track your research protocol.";
        if (alertBox) alertBox.classList.remove('hidden');
        return;
    }

    const scenarioSelect = document.getElementById('studentInputScenario');
    const selectedScenario = scenarioSelect.value;
    const fullScenarioText = scenarioSelect.options[scenarioSelect.selectedIndex].text;

    if (scenarioSelect.options[scenarioSelect.selectedIndex].disabled) {
        alertText.innerText = "Error: This scenario is currently locked or already completed.";
        if (alertBox) alertBox.classList.remove('hidden');
        return;
    }

    const shortScenarioTitle = fullScenarioText.split(' (')[0].split(':')[0].trim();
    const studentPastRecords = appState.studentRecords.filter(r => (r.student_id === inputId || r.id === inputId));
    const completedScenarios = studentPastRecords.map(r => r.scenario ? r.scenario.split(' (')[0].split(':')[0].trim() : '');

    // Fully concluded lockout check
    if (completedScenarios.includes('Scenario E')) {
        alertText.innerText = "Research Concluded: You have successfully completed the testing protocol! Thank you.";
        if (alertBox) alertBox.classList.remove('hidden');
        return;
    }

    // --- DAY 2 DATE RESTRICTION FIX ---
    const todayStr = new Date().toLocaleDateString();
    const recordsToday = studentPastRecords.filter(r => {
        let rDate = r.created_at ? new Date(r.created_at).toLocaleDateString() : new Date().toLocaleDateString();
        return rDate === todayStr;
    });

    if (recordsToday.length > 0) {
        const todayScenarios = recordsToday.map(r => r.scenario ? r.scenario.split(' (')[0].split(':')[0].trim() : '');

        // 1. If they did Day 1 (Scenario A) today, they are blocked from starting Day 2 items today
        if (todayScenarios.includes('Scenario A')) {
            alertText.innerText = "Protocol Rule: You have completed Day 1 requirements today. Please return tomorrow for Day 2.";
            if (alertBox) alertBox.classList.remove('hidden');
            return;
        }

        // 2. If they did Day 3 (Scenario E) today, they are finished
        if (todayScenarios.includes('Scenario E')) {
            alertText.innerText = "Research Concluded: You have already completed the final post-test today.";
            if (alertBox) alertBox.classList.remove('hidden');
            return;
        }

        // 3. On Day 2, allow consecutive plays (B -> C -> D). Only lock them out if they finished D today!
        if (todayScenarios.includes('Scenario D')) {
            alertText.innerText = "Protocol Rule: Day 2 completed. Please return tomorrow for the final post-test.";
            if (alertBox) alertBox.classList.remove('hidden');
            return;
        }
    }

    // Set flag to trigger the SUS survey on Scenario E completion
    isFinalDay = shortScenarioTitle.includes('Scenario E');
    if (alertBox) alertBox.classList.add('hidden');

    currentState = selectedScenario;
    deviations = 0;
    answerHistory = [];

    const inputCohort = document.getElementById('studentInputCohort').value;
    currentSession = {
        id: inputId,
        name: inputName,
        cohort: inputCohort,
        scenarioName: shortScenarioTitle,
        startState: selectedScenario,
        cumulativeLatency: 0, steps: 0, optimalSteps: 0,
        finalLatency: 0, finalEfficiency: 0, finalAccuracy: 0,
        attemptsRemaining: 3
    };

    if (document.getElementById('telemetryName')) document.getElementById('telemetryName').innerText = currentSession.name;
    if (document.getElementById('scenarioTitle')) document.getElementById('scenarioTitle').innerText = `Active Case: ${currentSession.scenarioName}`;
    if (document.getElementById('telemetryEfficiency')) document.getElementById('telemetryEfficiency').innerText = "100%";
    if (document.getElementById('telemetryDeviations')) document.getElementById('telemetryDeviations').innerText = "0";

    // Clear login input elements for the next cycle
    document.getElementById('studentInputName').value = '';
    document.getElementById('studentInputId').value = '';
    document.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

    showView('studentView');
    renderFSMNode();
}

        function selectOption(nextStateID, isOptimal, answerText) {
            let latencyElapsed = parseFloat(((Date.now() - nodeDisplayTime) / 1000).toFixed(2));
            currentSession.cumulativeLatency += latencyElapsed;
            currentSession.steps++;

            answerHistory.push({
                state: currentState,
                answer_chosen: answerText,
                was_correct: isOptimal,
                time_spent: latencyElapsed + 's'
            });

            if (isOptimal) {
                currentSession.optimalSteps++;
            } else {
                deviations++;
                document.getElementById('telemetryDeviations').innerText = deviations;
                let currentEff = Math.max(100 - (deviations * 35), 15);
                document.getElementById('telemetryEfficiency').innerText = currentEff + '%';
            }

            currentState = nextStateID;
            renderFSMNode();
        }

        function renderFSMNode() {
            const nodeData = triageFSM[currentState];
            document.getElementById('telemetryState').innerText = currentState;
            document.getElementById('nodePrompt').innerText = nodeData.prompt;

            const optionsWrapper = document.getElementById('optionsWrapper');
            optionsWrapper.innerHTML = '';

            nodeDisplayTime = Date.now();
            if (latencyTimer) clearInterval(latencyTimer);

            latencyTimer = setInterval(() => {
                let timeElapsed = ((Date.now() - nodeDisplayTime) / 1000).toFixed(2);
                document.getElementById('telemetryLatency').innerText = timeElapsed + 's';
            }, 60);

            if (nodeData.isTerminal) {
                clearInterval(latencyTimer);
                document.getElementById('telemetryLatency').innerText = "Stopped";

                currentSession.finalLatency = parseFloat(currentSession.cumulativeLatency.toFixed(1));
                currentSession.finalEfficiency = Math.max(100 - (deviations * 35), 15);
                currentSession.finalAccuracy = Math.round((currentSession.optimalSteps / currentSession.steps) * 100) || 0;

                if (deviations > 0) appState.hesitationCount.node_2 += 5;
                else appState.hesitationCount.node_1 += 2;

                let modalHtml = `
                    <div class="p-5 rounded-xl ${nodeData.tagColor} ${nodeData.textColor} shadow-lg text-center space-y-1 mb-4">
                        <span class="text-[10px] uppercase font-extrabold tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block">Final Decision Classification</span>
                        <h4 class="text-xl font-black">${nodeData.tagName}</h4>
                    </div>
                    <div class="space-y-3">
                `;

                answerHistory.forEach((step, idx) => {
                    const originalQuestion = triageFSM[step.state];
                    const rightOption = originalQuestion.options ? originalQuestion.options.find(o => o.optimal === true) : null;
                    const rightAnswerText = rightOption ? rightOption.text : "Protocol Specific Action";

                    modalHtml += `
                        <div class="p-3.5 bg-brand-bg border-2 ${step.was_correct ? 'border-brand-accent/20' : 'border-red-100'} rounded-xl text-xs space-y-1.5">
                            <p class="font-bold text-brand-dark leading-normal">
                                Question ${idx + 1}: <span class="font-normal text-brand-muted">${originalQuestion.prompt}</span>
                            </p>
                            <p class="font-semibold ${step.was_correct ? 'text-brand-accent bg-emerald-50/60' : 'text-red-500 bg-red-50/60'} px-2 py-1 rounded inline-block">
                                Your Choice: "${step.answer_chosen}" ${step.was_correct ? '✓ (Correct)' : '✗ (Mistake)'}
                            </p>
                            ${!step.was_correct ? `
                                <p class="text-brand-tealDark font-bold bg-brand-mintLight px-2 py-1 rounded">
                                    → Right Answer: "${rightAnswerText}"
                                </p>
                            ` : ''}
                        </div>
                    `;
                });

                modalHtml += `</div>`;

                let buttonHtml = '';
                if (isFinalDay) {
                    buttonHtml = `
                        <button onclick="closeResultAndOpenSUS()" class="w-full py-3.5 px-4 bg-brand-teal hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2 shadow-lg shadow-brand-teal/10">
                            <span>Proceed to Usability Survey (SUS)</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    `;
                } else {
                    const reattemptButton = currentSession.attemptsRemaining > 0 ? `
                        <button onclick="attemptReattempt()" class="w-full py-3.5 px-4 bg-white border-2 border-brand-mint hover:border-brand-teal text-brand-dark font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2 shadow-sm shadow-brand-mint/10">
                            <span>Reattempt this Quiz (${currentSession.attemptsRemaining} left)</span>
                            <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                        </button>
                    ` : `
                        <button disabled class="w-full py-3.5 px-4 bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2 shadow-sm">
                            <span>Locked. Continue tomorrow</span>
                            <i data-lucide="slash" class="w-4 h-4"></i>
                        </button>
                    `;

                    buttonHtml = `
                        <div class="grid gap-3 md:grid-cols-3">
                            ${reattemptButton}
                            <button onclick="openReviewModal()" class="w-full py-3.5 px-4 bg-white border-2 border-brand-mint hover:border-brand-teal text-brand-dark font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2 shadow-sm shadow-brand-mint/10">
                                <span>Review Answers</span>
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                            </button>
                            <button onclick="showLockWarningModal()" class="w-full py-3.5 px-4 bg-brand-accent hover:bg-brand-teal text-white font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2 shadow-lg shadow-brand-accent/10">
                                <span>Proceed to Next Quiz</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    `;
                }
                document.getElementById('resultModalButtonWrapper').innerHTML = buttonHtml;
                document.getElementById('resultModalContent').innerHTML = modalHtml;
                document.getElementById('resultModal').classList.remove('hidden');

                optionsWrapper.innerHTML = `
                    <div class="p-4 bg-brand-bg text-center rounded-xl border border-brand-mint border-dashed">
                        <p class="text-sm font-semibold text-brand-tealDark">Scenario Session Completed</p>
                        <button onclick="document.getElementById('resultModal').classList.remove('hidden')" class="mt-2 text-xs font-bold text-brand-teal hover:underline flex items-center justify-center gap-1 mx-auto">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Re-open Evaluation Modal
                        </button>
                    </div>
                `;
                lucide.createIcons();
                return;
            }

            nodeData.options.forEach(opt => {
                const button = document.createElement('button');
                button.className = "w-full text-left p-4 bg-white border-2 border-brand-mint hover:border-brand-teal rounded-xl text-brand-dark font-medium hover:bg-brand-mintLight transition-all flex justify-between items-center group";
                button.innerHTML = `
                    <span>${opt.text}</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-brand-teal opacity-0 group-hover:opacity-100 transition-all"></i>
                `;
                button.onclick = () => selectOption(opt.nextState, opt.optimal, opt.text);
                optionsWrapper.appendChild(button);
            });
            lucide.createIcons();
        }

        function dispatchSessionPayload(payload, successMessage) {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const finishSave = () => {
                appState.studentRecords.push(payload);
                refreshDashboard();
                document.getElementById('resultModal').classList.add('hidden');
                document.getElementById('susModal').classList.add('hidden');
                showSessionSavedModal(successMessage);
            };

            if (csrfMeta && csrfMeta.getAttribute('content')) {
                fetch('/api/sessions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => finishSave())
                .catch(err => {
                    console.error("Database sync exception: ", err);
                    finishSave();
                });
            } else {
                finishSave();
            }
        }

        function submitFinalSessionWithoutSUS() {
            showLockWarningModal();
        }

        function attemptReattempt() {
            if (!currentSession || currentSession.attemptsRemaining <= 0) return;
            currentSession.attemptsRemaining -= 1;
            currentState = currentSession.startState;
            deviations = 0;
            answerHistory = [];
            currentSession.cumulativeLatency = 0;
            currentSession.steps = 0;
            currentSession.optimalSteps = 0;
            currentSession.finalLatency = 0;
            currentSession.finalEfficiency = 0;
            currentSession.finalAccuracy = 0;
            document.getElementById('resultModal').classList.add('hidden');
            renderFSMNode();
        }

        function showLockWarningModal() {
            document.getElementById('lockWarningModal').classList.remove('hidden');
        }

        function closeLockWarningModal() {
            document.getElementById('lockWarningModal').classList.add('hidden');
        }

        function confirmLockAndReview() {
            closeLockWarningModal();
            shouldLockAfterReview = true;
            openReviewModal();
        }

        function lockReattemptAfterReview() {
            currentSession.attemptsRemaining = 0;
            const wrapper = document.getElementById('resultModalButtonWrapper');
            if (!wrapper) return;
            wrapper.innerHTML = `
                <div class="text-xs text-brand-muted mb-3">This quiz has been locked. Continue tomorrow to retry.</div>
                <button disabled class="w-full py-3.5 px-4 bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2 shadow-sm">
                    <span>Locked. Continue tomorrow</span>
                    <i data-lucide="slash" class="w-4 h-4"></i>
                </button>
            `;
        }

        function openReviewModal() {
            renderReviewModal();
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function submitFinalSessionConfirmed() {
            document.getElementById('saveConfirmModal').classList.remove('hidden');
        }

        function closeSaveConfirmModal() {
            document.getElementById('saveConfirmModal').classList.add('hidden');
        }

        function showSessionSavedModal(message) {
            const messageNode = document.getElementById('sessionSavedMessage');
            if (messageNode) {
                messageNode.textContent = message;
            }
            document.getElementById('sessionSavedModal').classList.remove('hidden');
        }

        function closeSessionSavedModal() {
            document.getElementById('sessionSavedModal').classList.add('hidden');
            showView('homeView');
        }

        function confirmSaveSession() {
            lockReattemptAfterReview();
            const payload = {
                student_id: currentSession.id,
                student_name: currentSession.name,
                cohort: currentSession.cohort,
                scenario: currentSession.scenarioName,
                latency: currentSession.finalLatency === 0 ? 4.5 : currentSession.finalLatency,
                efficiency: currentSession.finalEfficiency,
                accuracy: currentSession.finalAccuracy,
                path_log: JSON.stringify(answerHistory),
                sus_responses: null,
                sus_score: 0,
                created_at: new Date().toISOString()
            };
            closeSaveConfirmModal();
            dispatchSessionPayload(payload, "Session Saved! You have completed today's requirement. Please return tomorrow.");
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            if (shouldLockAfterReview) {
                openSaveConfirmModal();
                shouldLockAfterReview = false;
            }
        }

        function openSaveConfirmModal() {
            document.getElementById('saveConfirmModal').classList.remove('hidden');
        }

        function renderReviewModal() {
            const content = document.getElementById('reviewModalContent');
            const accuracy = currentSession.finalAccuracy || 0;
            const efficiency = currentSession.finalEfficiency || 0;
            const latency = currentSession.finalLatency || 0;
            const correctCount = currentSession.optimalSteps || 0;
            const totalCount = currentSession.steps || answerHistory.length || 0;

            let reviewHtml = `
                <div class="p-4 rounded-2xl bg-brand-bg border border-brand-mint text-sm space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-brand-dark font-bold">Accuracy Preview</p>
                            <p class="text-xs text-brand-muted">Correct decisions vs total steps.</p>
                        </div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-accent/10 text-brand-accent text-xs font-semibold">${accuracy}%</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-xs text-brand-muted">
                        <div class="rounded-xl bg-white p-3 border border-brand-mint">
                            <p class="font-semibold text-brand-dark">Correct Answers</p>
                            <p>${correctCount} / ${totalCount}</p>
                        </div>
                        <div class="rounded-xl bg-white p-3 border border-brand-mint">
                            <p class="font-semibold text-brand-dark">Path Efficiency</p>
                            <p>${efficiency}%</p>
                        </div>
                        <div class="rounded-xl bg-white p-3 border border-brand-mint col-span-2">
                            <p class="font-semibold text-brand-dark">Total Latency</p>
                            <p>${latency}s</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
            `;

            answerHistory.forEach((step, idx) => {
                const originalQuestion = triageFSM[step.state] || {};
                const rightOption = originalQuestion.options ? originalQuestion.options.find(o => o.optimal === true) : null;
                const rightAnswerText = rightOption ? rightOption.text : 'Protocol Specific Action';
                reviewHtml += `
                    <div class="p-4 rounded-2xl bg-white border ${step.was_correct ? 'border-brand-accent/20' : 'border-red-100'} shadow-sm">
                        <div class="flex items-center justify-between gap-3 mb-2 text-xs text-brand-muted">
                            <span>Step ${idx + 1}</span>
                            <span class="${step.was_correct ? 'text-brand-accent' : 'text-red-500'} font-bold">${step.was_correct ? 'Correct' : 'Incorrect'}</span>
                        </div>
                        <p class="font-semibold text-brand-dark mb-2">${originalQuestion.prompt || 'Review this decision.'}</p>
                        <p class="text-sm text-brand-dark"><span class="font-bold">Your Answer:</span> ${step.answer_chosen}</p>
                        <p class="text-sm text-brand-muted"><span class="font-bold">Expected:</span> ${rightAnswerText}</p>
                        <p class="text-sm text-brand-muted">Time Spent: ${step.time_spent}</p>
                    </div>
                `;
            });

            reviewHtml += `</div>`;
            content.innerHTML = reviewHtml;
        }

        function submitFinalSessionWithSUS() {
            let answers = {};
            let allAnswered = true;

            for (let i = 1; i <= 10; i++) {
                const selected = document.querySelector(`input[name="sus_q_${i}"]:checked`);
                if (!selected) {
                    allAnswered = false;
                    break;
                }
                answers[`q${i}`] = parseInt(selected.value);
            }

            if (!allAnswered) {
                alert("Research Requirement: Please answer all 10 System Usability Scale questions before submitting your final data.");
                return;
            }

            let oddSum = (answers.q1 - 1) + (answers.q3 - 1) + (answers.q5 - 1) + (answers.q7 - 1) + (answers.q9 - 1);
            let evenSum = (5 - answers.q2) + (5 - answers.q4) + (5 - answers.q6) + (5 - answers.q8) + (5 - answers.q10);
            let finalSUSMultiplierScore = (oddSum + evenSum) * 2.5;

            const payload = {
                student_id: currentSession.id,
                student_name: currentSession.name,
                cohort: currentSession.cohort,
                scenario: currentSession.scenarioName,
                latency: currentSession.finalLatency === 0 ? 4.5 : currentSession.finalLatency,
                efficiency: currentSession.finalEfficiency,
                accuracy: currentSession.finalAccuracy,
                path_log: JSON.stringify(answerHistory),
                sus_responses: JSON.stringify(answers),
                sus_score: finalSUSMultiplierScore,
                created_at: new Date().toISOString()
            };
            dispatchSessionPayload(payload, `Final Protocol Complete!\nYour Final SUS Score: ${finalSUSMultiplierScore}`);
        }

        // ============================================
        // INSTRUCTOR DASHBOARD JAVASCRIPT ENGINE
        // ============================================

        function toggleFilterPanel() {
            const filterPanel = document.getElementById('filterPanel');
            const arrowIcon = document.getElementById('arrowIcon');
            const toggleFilterBtn = document.getElementById('toggleFilterBtn');

            if (filterPanel.style.maxHeight === '0px' || !filterPanel.style.maxHeight) {
                filterPanel.style.maxHeight = '300px';
                arrowIcon.style.transform = 'rotate(180deg)';
                toggleFilterBtn.classList.add('border-brand-teal', 'bg-brand-mintLight');
            } else {
                filterPanel.style.maxHeight = '0px';
                arrowIcon.style.transform = 'rotate(0deg)';
                toggleFilterBtn.classList.remove('border-brand-teal', 'bg-brand-mintLight');
            }
        }

        function processTableFilters() {
            const query = document.getElementById('studentSearch').value.toLowerCase().trim();
            const targetScenario = document.getElementById('filterScenario').value;
            const tableBody = document.getElementById('studentTableBody');
            const rows = Array.from(tableBody.getElementsByClassName('student-row'));

            rows.forEach(row => {
                const sid = row.getAttribute('data-id').toLowerCase();
                const sname = row.getAttribute('data-name');
                const scenario = row.getAttribute('data-scenario');

                const matchesSearch = sid.includes(query) || sname.includes(query);
                const matchesScenario = (targetScenario === 'all' || scenario.includes(targetScenario));

                if (matchesSearch && matchesScenario) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function executeTableSort() {
            const method = document.getElementById('sortRecords').value;
            const tableBody = document.getElementById('studentTableBody');
            const rows = Array.from(tableBody.getElementsByClassName('student-row'));

            const dynamicSortingOrder = [...rows].sort((rowA, rowB) => {
                if (method === 'name-asc') {
                    return rowA.getAttribute('data-name').localeCompare(rowB.getAttribute('data-name'));
                }
                if (method === 'name-desc') {
                    return rowB.getAttribute('data-name').localeCompare(rowA.getAttribute('data-name'));
                }
                if (method === 'time-fastest') {
                    return parseFloat(rowA.getAttribute('data-latency')) - parseFloat(rowB.getAttribute('data-latency'));
                }
                if (method === 'time-slowest') {
                    return parseFloat(rowB.getAttribute('data-latency')) - parseFloat(rowA.getAttribute('data-latency'));
                }
                if (method === 'accuracy-highest') {
                    return parseInt(rowB.getAttribute('data-accuracy')) - parseInt(rowA.getAttribute('data-accuracy'));
                }
                if (method === 'sus-highest') {
                    return parseFloat(rowB.getAttribute('data-sus')) - parseFloat(rowA.getAttribute('data-sus'));
                }
                return 0; // default
            });

            // Reinsert cleanly back into the DOM
            dynamicSortingOrder.forEach(matchedRow => tableBody.appendChild(matchedRow));
        }

        function refreshDashboard() {
            const records = appState.studentRecords;
            document.getElementById('dash-total-students').innerText = records.length;

            if (records.length === 0) return;

            let totalLat = 0, totalEff = 0, totalAcc = 0;
            records.forEach(r => {
                totalLat += parseFloat(r.latency);
                totalEff += parseInt(r.efficiency);
                totalAcc += parseInt(r.accuracy);
            });

            const avgLat = (totalLat / records.length).toFixed(1);
            const avgEff = Math.round(totalEff / records.length);
            const avgAcc = Math.round(totalAcc / records.length);

            document.getElementById('dash-avg-latency').innerText = avgLat + 's';
            document.getElementById('dash-avg-efficiency').innerHTML = `<span class="px-2 py-0.5 bg-brand-bg rounded-lg text-brand-accent">${avgEff}%</span>`;
            document.getElementById('dash-avg-accuracy').innerText = avgAcc + '%';

            if (parseFloat(avgLat) <= 6.0) {
                document.getElementById('dash-avg-latency').className = "text-3xl font-black text-brand-accent leading-tight mt-2";
            } else {
                document.getElementById('dash-avg-latency').className = "text-3xl font-black text-red-500 leading-tight mt-2";
            }

            const tbody = document.getElementById('studentTableBody');
            tbody.innerHTML = '';

            if (records.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="p-10 text-center text-brand-muted italic">
                        📭 No performance logs available in database.
                    </td>
                </tr>`;
            } else {
                [...records].reverse().forEach(r => {
                    const name = r.student_name || r.name || 'Unknown';
                    const id = r.student_id || r.id || 'N/A';
                    const cohort = r.cohort || 'N/A';
                    const scenario = r.scenario || 'Scenario A';
                    const shortScenario = scenario.split(':')[0];

                    const susDisplay = (r.sus_score && r.sus_score > 0) ? Number(r.sus_score).toFixed(1) : 'N/A';
                    const lat = parseFloat(r.latency).toFixed(2);
                    const acc = parseInt(r.accuracy);
                    const accColor = acc >= 80 ? '#00a887' : '#ef4444';

                    let dateObj = r.created_at ? new Date(r.created_at) : new Date();
                    let dateDisplay = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                    tbody.innerHTML += `
                        <tr class="student-row hover:bg-brand-mintLight/50 transition-colors"
                            data-id="${id}"
                            data-name="${name.toLowerCase()}"
                            data-scenario="${scenario}"
                            data-latency="${lat}"
                            data-accuracy="${acc}"
                            data-sus="${r.sus_score || 0}">

                            <td class="p-4 font-mono text-brand-teal font-bold">${id}</td>
                            <td class="p-4 font-bold text-brand-dark">${name}</td>
                            <td class="p-4"><span class="bg-brand-bg border border-brand-mint px-3 py-1 rounded-full text-[11px] font-bold text-brand-muted uppercase tracking-wider">${cohort}</span></td>
                            <td class="p-4 text-brand-dark font-medium">${scenario}</td>
                            <td class="p-4 text-amber-600 font-bold">${lat}s</td>
                            <td class="p-4">
                                <span class="font-bold" style="color: ${accColor};">
                                    ${acc}%
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-purple-600">${susDisplay}</td>
                        </tr>
                    `;
                });
            }

            // Top Paths Ranking Code
            const sorted = [...records].sort((a, b) => {
                if (b.efficiency !== a.efficiency) return b.efficiency - a.efficiency;
                return a.latency - b.latency;
            });

            const rankingList = document.getElementById('dash-ranking-list');
            rankingList.innerHTML = '';
            sorted.slice(0, 3).forEach((r, idx) => {
                let badgeStyle = "bg-yellow-400 text-yellow-900";
                if (idx === 1) badgeStyle = "bg-slate-300 text-slate-800";
                if (idx === 2) badgeStyle = "bg-amber-600 text-amber-100";
                const name = r.student_name || r.name;

                rankingList.innerHTML += `
                    <div class="flex items-center justify-between p-3.5 bg-brand-bg border border-brand-mint rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 ${badgeStyle} rounded-full font-bold flex items-center justify-center text-xs shadow-sm">${idx + 1}</div>
                            <div>
                                <p class="text-xs font-bold text-brand-dark">${name}</p>
                                <p class="text-[10px] text-brand-muted">${r.accuracy}% Accuracy | ${r.efficiency}% Efficiency</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-brand-teal">${r.latency}s</span>
                    </div>
                `;
            });

            const h1 = Math.min(appState.hesitationCount.node_1, 100);
            const h2 = Math.min(appState.hesitationCount.node_2, 100);
            const h3 = Math.min(appState.hesitationCount.node_3, 100);

            document.getElementById('heatmapContainer').innerHTML = `
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-brand-dark">
                        <span>node-1: Assess Breathing (START Opening)</span>
                        <span class="text-brand-muted">${h1}% hesitation avg</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-teal h-full" style="width: ${h1}%"></div>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-brand-dark">
                        <span>node-2: Check Respiratory Rate Rate-limit</span>
                        <span class="font-bold ${h2 > 20 ? 'text-red-500' : 'text-brand-muted'}">${h2}% hesitation avg</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full" style="width: ${h2}%"></div>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-brand-dark">
                        <span>node-3: Check Capillary Refill Time (CRT)</span>
                        <span class="text-brand-muted">${h3}% hesitation avg</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-accent h-full" style="width: ${h3}%"></div>
                    </div>
                </div>
            `;
            lucide.createIcons();

            // Re-apply any active filters/sorts after reloading the data
            if(document.getElementById('studentSearch')) {
                processTableFilters();
                executeTableSort();
            }
        }

        window.onload = function() {
            refreshDashboard();
            lucide.createIcons();
            updateScenarioOptionsByDay();

            // Bind listeners for dynamic filters
            document.getElementById('studentSearch').addEventListener('input', processTableFilters);
            document.getElementById('filterScenario').addEventListener('change', () => { processTableFilters(); executeTableSort(); });
            document.getElementById('sortRecords').addEventListener('change', executeTableSort);
        }
    </script>
</body>

</html>
