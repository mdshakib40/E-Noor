
<!-- ========================================================= -->
<!-- LOGIN FORM -->
<!-- ========================================================= -->

<form
    id="loginForm"
    action="{{ route('login.submit') }}"
    method="POST"
>

    @csrf

    @if (session('open_auth_modal') === 'login' && $errors->any())

        <div
            class="mb-4 rounded-[11px]
                   border border-red-500/20
                   bg-red-500/10
                   px-3 py-3
                   text-[10px]
                   leading-[1.5]
                   text-red-600"
        >
            {{ $errors->first() }}
        </div>

    @endif

    <h3 class="font-serifNoor text-[39px] leading-[.95]">
        Welcome back.
    </h3>

    <p class="my-[10px] mb-[25px] text-[11px] leading-[1.7] text-[#756e65]">
        Pick up where you left off and find something worth ordering.
    </p>

    <!-- EMAIL -->

    <div>

        <label
            class="text-[9px] font-extrabold uppercase tracking-[.1em]"
        >
            Email
        </label>

        <input
            name="email"
            value="{{ session('open_auth_modal') === 'login' ? old('email') : '' }}"
            required
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                   bg-white px-[13px] py-[13px] text-[12px] outline-none
                   focus:border-[#b07d31]"
        >

    </div>

    <!-- PASSWORD -->

    <div class="mt-[13px]">

        <label
            class="text-[9px] font-extrabold uppercase tracking-[.1em]"
        >
            Password
        </label>

        <div class="relative">

            <input
                id="loginPassword"
                name="password"
                required
                type="password"
                placeholder="Your password"
                autocomplete="current-password"
                class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                       bg-white px-[13px] py-[13px] pr-[43px] text-[12px]
                       outline-none focus:border-[#b07d31]"
            >

            <button
                type="button"
                data-password="loginPassword"
                class="absolute right-[11px] top-1/2 grid
                       -translate-y-1/2 place-items-center"
            >
                <i data-lucide="eye" class="w-[15px]"></i>
            </button>

        </div>

    </div>

    <!-- REMEMBER -->

    <div
        class="mt-[14px] flex items-center justify-between
               text-[10px]"
    >

        <label class="flex items-center gap-2">

            <input
                type="checkbox"
                name="remember"
                value="1"
            >

            Keep me signed in

        </label>

        <button
            id="forgotPasswordButton"
            type="button"
            class="font-extrabold text-[#9d6c25]"
        >
            Forgot password?
        </button>

    </div>

    <!-- LOGIN BUTTON -->

    <button
        type="submit"
        data-loading-button
        class="mt-5 flex w-full items-center justify-center gap-2
               rounded-[11px] bg-night px-4 py-[14px]
               text-[11px] font-extrabold text-white"
    >

        <span class="button-text">
            Continue
        </span>

        <i data-lucide="arrow-right" class="w-[15px]"></i>

    </button>

</form>


<!-- ========================================================= -->
<!-- REGISTER FORM -->
<!-- ========================================================= -->

<form
    id="joinForm"
    action="{{ route('register') }}"
    method="POST"
    class="hidden"
>

    @csrf

    @if (session('open_auth_modal') === 'register' && $errors->any())

        <div
            class="mb-4 rounded-[11px]
                   border border-red-500/20
                   bg-red-500/10
                   px-3 py-3
                   text-[10px]
                   leading-[1.5]
                   text-red-600"
        >
            {{ $errors->first() }}
        </div>

    @endif

    <h3 class="font-serifNoor text-[39px] leading-[.95]">
        Make a table.
    </h3>

    <p class="my-[10px] mb-[25px] text-[11px] leading-[1.7] text-[#756e65]">
        Create an account for saved favourites, offers, and easier orders.
    </p>

    <!-- NAME -->

    <div>

        <label class="text-[9px] font-extrabold uppercase tracking-[.1em]">
            Full Name
        </label>

        <input
            name="name"
            value="{{ session('open_auth_modal') === 'register' ? old('name') : '' }}"
            required
            autocomplete="name"
            placeholder="Your name"
            class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                   bg-white px-[13px] py-[13px] text-[12px] outline-none
                   focus:border-[#b07d31]"
        >

    </div>

    <!-- EMAIL -->

    <div class="mt-[13px]">

        <label class="text-[9px] font-extrabold uppercase tracking-[.1em]">
            Email
        </label>

        <input
            name="email"
            value="{{ session('open_auth_modal') === 'register' ? old('email') : '' }}"
            required
            type="email"
            autocomplete="email"
            placeholder="you@example.com"
            class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                   bg-white px-[13px] py-[13px] text-[12px] outline-none
                   focus:border-[#b07d31]"
        >

    </div>

    <!-- PASSWORD -->

    <div class="mt-[13px]">

        <label class="text-[9px] font-extrabold uppercase tracking-[.1em]">
            Create password
        </label>

        <div class="relative">

            <input
                id="joinPassword"
                name="password"
                required
                minlength="8"
                type="password"
                autocomplete="new-password"
                placeholder="At least 8 characters"
                class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                       bg-white px-[13px] py-[13px] pr-[43px] text-[12px]
                       outline-none focus:border-[#b07d31]"
            >

            <button
                type="button"
                data-password="joinPassword"
                class="absolute right-[11px] top-1/2 grid
                       -translate-y-1/2 place-items-center"
            >
                <i data-lucide="eye" class="w-[15px]"></i>
            </button>

        </div>

    </div>

    <!-- CONFIRM PASSWORD -->

    <div class="mt-[13px]">

        <label class="text-[9px] font-extrabold uppercase tracking-[.1em]">
            Confirm password
        </label>

        <div class="relative">

            <input
                id="joinPasswordConfirmation"
                name="password_confirmation"
                required
                minlength="8"
                type="password"
                autocomplete="new-password"
                placeholder="Repeat your password"
                class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                       bg-white px-[13px] py-[13px] pr-[43px] text-[12px]
                       outline-none focus:border-[#b07d31]"
            >

            <button
                type="button"
                data-password="joinPasswordConfirmation"
                class="absolute right-[11px] top-1/2 grid
                       -translate-y-1/2 place-items-center"
            >
                <i data-lucide="eye" class="w-[15px]"></i>
            </button>

        </div>

    </div>

    <!-- REGISTER BUTTON -->

    <button
        type="submit"
        data-loading-button
        class="mt-5 flex w-full items-center justify-center gap-2
               rounded-[11px] bg-night px-4 py-[14px]
               text-[11px] font-extrabold text-white"
    >

        <span class="button-text">
            Create account
        </span>

        <i data-lucide="arrow-right" class="w-[15px]"></i>

    </button>

</form>


<!-- ========================================================= -->
<!-- FORGOT PASSWORD FORM -->
<!-- ========================================================= -->

<form
    id="forgotForm"
    action="{{ route('password.forgot') }}"
    method="POST"
    class="hidden"
>

    @csrf

    @if (
        session('open_auth_modal') === 'forgot' &&
        ($errors->has('forgot_email') || $errors->has('email'))
    )

        <div
            class="mb-4 rounded-[11px]
                   border border-red-500/20
                   bg-red-500/10
                   px-3 py-3
                   text-[10px]
                   leading-[1.5]
                   text-red-600"
        >
            {{ $errors->first('forgot_email') ?: $errors->first('email') }}
        </div>

    @endif

    <h3 class="font-serifNoor text-[39px] leading-[.95]">
        Forgot password?
    </h3>

    <p class="my-[10px] mb-[25px] text-[11px] leading-[1.7] text-[#756e65]">
        Enter the email connected to your NOOR account and we'll help you reset your password.
    </p>

    <!-- EMAIL -->

    <div>

        <label class="text-[9px] font-extrabold uppercase tracking-[.1em]">
            Email
        </label>

        <input
            id="forgotEmail"
            name="email"
            value="{{ session('open_auth_modal') === 'forgot' ? old('email') : '' }}"
            required
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                   bg-white px-[13px] py-[13px] text-[12px] outline-none
                   focus:border-[#b07d31]"
        >

    </div>

    <!-- SEND CODE -->

    <button
        type="submit"
        data-loading-button
        class="mt-5 flex w-full items-center justify-center gap-2
               rounded-[11px] bg-night px-4 py-[14px]
               text-[11px] font-extrabold text-white"
    >

        <span class="button-text">
            Send verification code
        </span>

        <i data-lucide="arrow-right" class="w-[15px]"></i>

    </button>

    <!-- BACK -->

    <button
        id="forgotBackButton"
        type="button"
        class="mt-4 flex w-full items-center justify-center gap-2
               text-[10px] font-extrabold text-[#9d6c25]"
    >

        <i data-lucide="arrow-left" class="w-[13px]"></i>

        Back to sign in

    </button>

</form>


<!-- ========================================================= -->
<!-- VERIFICATION CODE FORM -->
<!-- ========================================================= -->

<form
    id="verificationForm"
    action="{{ route('password.verify.code') }}"
    method="POST"
    class="hidden"
>

    @csrf

    @if (
        session('open_auth_modal') === 'verification' &&
        (
            $errors->has('verification_code') ||
            $errors->has('email') ||
            $errors->has('code')
        )
    )

        <div
            class="mb-4 rounded-[11px]
                   border border-red-500/20
                   bg-red-500/10
                   px-3 py-3
                   text-[10px]
                   leading-[1.5]
                   text-red-600"
        >
            {{ $errors->first('verification_code')
                ?: ($errors->first('code') ?: $errors->first('email')) }}
        </div>

    @endif

    <h3 class="font-serifNoor text-[39px] leading-[.95]">
        Check your email.
    </h3>

    <p class="my-[10px] mb-[25px] text-[11px] leading-[1.7] text-[#756e65]">
        Enter the 6-digit verification code we sent to your email.
    </p>

    <!-- EMAIL -->

    <input
        type="hidden"
        name="email"
        value="{{ old('email') }}"
    >

    <!-- CODE -->

    <div>

        <label
            for="verificationCode"
            class="text-[9px] font-extrabold uppercase tracking-[.1em]"
        >
            Verification Code
        </label>

        <input
            id="verificationCode"
            name="code"
            value="{{ old('code') }}"
            required
            type="text"
            inputmode="numeric"
            maxlength="6"
            autocomplete="one-time-code"
            placeholder="000000"
            class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                   bg-white px-[13px] py-[13px] text-center text-[20px]
                   font-extrabold tracking-[.45em] outline-none
                   focus:border-[#b07d31]"
        >

    </div>

    <!-- VERIFY -->

    <button
        type="submit"
        data-loading-button
        class="mt-5 flex w-full items-center justify-center gap-2
               rounded-[11px] bg-night px-4 py-[14px]
               text-[11px] font-extrabold text-white"
    >

        <span class="button-text">
            Verify code
        </span>

        <i data-lucide="check" class="w-[15px]"></i>

    </button>

    <!-- BACK -->

    <button
        id="verificationBackButton"
        type="button"
        class="mt-4 flex w-full items-center justify-center gap-2
               text-[10px] font-extrabold text-[#9d6c25]"
    >

        <i data-lucide="arrow-left" class="w-[13px]"></i>

        Back

    </button>

</form>


<!-- ========================================================= -->
<!-- RESET PASSWORD FORM -->
<!-- ========================================================= -->

<form
    id="resetPasswordForm"
    action="{{ route('password.update') }}"
    method="POST"
    class="hidden"
>

    @csrf

    @if (
        session('open_auth_modal') === 'reset-password' &&
        $errors->any()
    )

        <div
            class="mb-4 rounded-[11px]
                   border border-red-500/20
                   bg-red-500/10
                   px-3 py-3
                   text-[10px]
                   leading-[1.5]
                   text-red-600"
        >
            {{ $errors->first() }}
        </div>

    @endif

    <h3 class="font-serifNoor text-[39px] leading-[.95]">
        New password.
    </h3>

    <p class="my-[10px] mb-[25px] text-[11px] leading-[1.7] text-[#756e65]">
        Create a new password for your NOOR account.
    </p>

    <!-- NEW PASSWORD -->

    <div>

        <label
            class="text-[9px] font-extrabold uppercase tracking-[.1em]"
        >
            New password
        </label>

        <div class="relative">

            <input
                id="resetPassword"
                name="password"
                required
                minlength="8"
                type="password"
                autocomplete="new-password"
                placeholder="At least 8 characters"
                class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                       bg-white px-[13px] py-[13px] pr-[43px] text-[12px]
                       outline-none focus:border-[#b07d31]"
            >

            <button
                type="button"
                data-password="resetPassword"
                class="absolute right-[11px] top-1/2 grid
                       -translate-y-1/2 place-items-center"
            >
                <i data-lucide="eye" class="w-[15px]"></i>
            </button>

        </div>

    </div>

    <!-- CONFIRM PASSWORD -->

    <div class="mt-[13px]">

        <label
            class="text-[9px] font-extrabold uppercase tracking-[.1em]"
        >
            Confirm password
        </label>

        <div class="relative">

            <input
                id="resetPasswordConfirmation"
                name="password_confirmation"
                required
                minlength="8"
                type="password"
                autocomplete="new-password"
                placeholder="Repeat your password"
                class="mt-[6px] w-full rounded-[11px] border border-black/[.125]
                       bg-white px-[13px] py-[13px] pr-[43px] text-[12px]
                       outline-none focus:border-[#b07d31]"
            >

            <button
                type="button"
                data-password="resetPasswordConfirmation"
                class="absolute right-[11px] top-1/2 grid
                       -translate-y-1/2 place-items-center"
            >
                <i data-lucide="eye" class="w-[15px]"></i>
            </button>

        </div>

    </div>

    <!-- UPDATE PASSWORD -->

    <button
        type="submit"
        data-loading-button
        class="mt-5 flex w-full items-center justify-center gap-2
               rounded-[11px] bg-night px-4 py-[14px]
               text-[11px] font-extrabold text-white"
    >

        <span class="button-text">
            Update password
        </span>

        <i data-lucide="check" class="w-[15px]"></i>

    </button>

</form>


<!-- ========================================================= -->
<!-- PASSWORD RESET SUCCESS -->
<!-- ========================================================= -->

<div
    id="passwordResetSuccess"
    class="hidden text-center"
>

    <!-- SUCCESS ICON -->

    <div
        class="mx-auto mb-6 grid h-[78px] w-[78px]
               place-items-center rounded-full
               border border-[#2f9e44]/20
               bg-[#2f9e44]/10
               animate-[successPop_.5s_ease-out]"
    >

        <div
            class="grid h-[56px] w-[56px]
                   place-items-center rounded-full
                   bg-[#2f9e44]/15"
        >

            <i
                data-lucide="check"
                class="w-[28px] text-[#2f9e44]
                       animate-[successCheck_.5s_ease-out_.15s_both]"
            ></i>

        </div>

    </div>


    <!-- TITLE -->

    <h3 class="font-serifNoor text-[39px] leading-[.95]">
        You're all set.
    </h3>


    <!-- MESSAGE -->

    <p
        class="mx-auto my-[10px] mb-[25px]
               max-w-[270px]
               text-[11px]
               leading-[1.7]
               text-[#756e65]"
    >
        Your password has been updated successfully.
        You can now sign in with your new password.
    </p>


    <!-- LOGIN NOW -->

    <button
        id="loginNowButton"
        type="button"
        class="mt-2 flex w-full items-center justify-center gap-2
               rounded-[11px] bg-night px-4 py-[14px]
               text-[11px] font-extrabold text-white
               transition-all duration-200
               hover:-translate-y-[1px]"
    >

        <span>
            Login now
        </span>

        <i data-lucide="arrow-right" class="w-[15px]"></i>

    </button>

</div>


<!-- ========================================================= -->
<!-- SUCCESS ANIMATIONS -->
<!-- ========================================================= -->

<style>

@keyframes successPop {

    0% {
        transform: scale(.5);
        opacity: 0;
    }

    70% {
        transform: scale(1.08);
        opacity: 1;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }

}

@keyframes successCheck {

    0% {
        transform: scale(0) rotate(-45deg);
        opacity: 0;
    }

    100% {
        transform: scale(1) rotate(0);
        opacity: 1;
    }

}

</style>


<!-- ========================================================= -->
<!-- SWITCH -->
<!-- ========================================================= -->

<div
    id="authSwitchWrapper"
    class="mt-6 text-center text-[10px] text-[#777067]"
>

    <span id="authText">
        New to NOOR?
    </span>

    <button
        id="authSwitch"
        type="button"
        class="ml-1 font-extrabold text-[#9d6c25]"
    >
        Create an account
    </button>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize icons
    if (window.lucide) window.lucide.createIcons();

    // Mobile menu
    const mobileBtn = document.getElementById('mobileBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    function setMobileMenu(open) {
        if (!mobileBtn || !mobileMenu) return;

        mobileMenu.classList.toggle('hidden', !open);
        mobileBtn.setAttribute('aria-expanded', String(open));

        const icon = mobileBtn.querySelector('[data-lucide]');
        if (icon) {
            icon.setAttribute('data-lucide', open ? 'x' : 'menu');
            if (window.lucide) window.lucide.createIcons();
        }
    }

    if (mobileBtn && mobileMenu) {
        mobileBtn.setAttribute('aria-expanded', 'false');

        mobileBtn.addEventListener('click', function () {
            setMobileMenu(mobileMenu.classList.contains('hidden'));
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => setMobileMenu(false));
        });

        mobileMenu.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => setMobileMenu(false));
        });
    }

    // Search overlay
    const navSearch = document.getElementById('navSearch');
    const heroSearch = document.getElementById('heroSearch');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchInput = document.getElementById('searchInput');
    const searchClose = document.getElementById('searchClose');

    function openSearch() {
        if (!searchOverlay) return;

        searchOverlay.classList.remove('hidden');
        searchOverlay.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        if (window.lucide) window.lucide.createIcons();
        setTimeout(() => searchInput?.focus(), 50);
    }

    function closeSearch() {
        if (!searchOverlay) return;

        searchOverlay.classList.add('hidden');
        searchOverlay.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    navSearch?.addEventListener('click', openSearch);
    heroSearch?.addEventListener('click', openSearch);
    searchClose?.addEventListener('click', closeSearch);

    searchOverlay?.addEventListener('click', function (event) {
        if (event.target === searchOverlay) closeSearch();
    });

    // Live search across restaurant and food cards
    const searchableCards = document.querySelectorAll('[data-search]');

    function filterSearch() {
        if (!searchInput) return;

        const query = searchInput.value.trim().toLocaleLowerCase();

        searchableCards.forEach(card => {
            const text = (card.getAttribute('data-search') || '')
                .toLocaleLowerCase();

            card.classList.toggle(
                'hidden',
                query !== '' && !text.includes(query)
            );
        });
    }

    searchInput?.addEventListener('input', filterSearch);

    searchInput?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            closeSearch();

            document.getElementById('discover')?.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSearch();
            closeModal();
        }

        if (
            event.key === '/' &&
            !['INPUT', 'TEXTAREA'].includes(
                document.activeElement?.tagName
            )
        ) {
            event.preventDefault();
            openSearch();
        }
    });

    // Auth modal elements
    const authModal = document.getElementById('modal');
    const loginForm = document.getElementById('loginForm');
    const joinForm = document.getElementById('joinForm');
    const forgotForm = document.getElementById('forgotForm');
    const verificationForm = document.getElementById('verificationForm');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const passwordResetSuccess = document.getElementById('passwordResetSuccess');

    const loginTab = document.getElementById('loginTab');
    const joinTab = document.getElementById('joinTab');
    const authSwitch = document.getElementById('authSwitch');
    const authText = document.getElementById('authText');
    const authSwitchWrapper = document.getElementById('authSwitchWrapper');

    const closeModalButton = document.getElementById('closeModal');
    const forgotPasswordButton = document.getElementById('forgotPasswordButton');
    const forgotBackButton = document.getElementById('forgotBackButton');
    const verificationBackButton = document.getElementById('verificationBackButton');
    const loginNowButton = document.getElementById('loginNowButton');

    function openModal() {
        if (!authModal) return;

        closeSearch();
        authModal.classList.remove('hidden');
        authModal.classList.add('grid');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        if (!authModal) return;

        authModal.classList.add('hidden');
        authModal.classList.remove('grid');
        document.body.classList.remove('overflow-hidden');
    }

    function hideAllScreens() {
        [
            loginForm,
            joinForm,
            forgotForm,
            verificationForm,
            resetPasswordForm,
            passwordResetSuccess
        ].forEach(element => element?.classList.add('hidden'));
    }

    function resetTabs() {
        [loginTab, joinTab].forEach(tab => {
            tab?.classList.remove(
                'bg-white',
                'shadow-[0_4px_13px_#00000010]'
            );
        });
    }

    function showLogin() {
        hideAllScreens();
        resetTabs();

        loginForm?.classList.remove('hidden');
        loginTab?.classList.add(
            'bg-white',
            'shadow-[0_4px_13px_#00000010]'
        );

        if (authText) authText.textContent = 'New to NOOR?';
        if (authSwitch) authSwitch.textContent = 'Create an account';
        authSwitchWrapper?.classList.remove('hidden');
    }

    function showRegister() {
        hideAllScreens();
        resetTabs();

        joinForm?.classList.remove('hidden');
        joinTab?.classList.add(
            'bg-white',
            'shadow-[0_4px_13px_#00000010]'
        );

        if (authText) authText.textContent = 'Already have an account?';
        if (authSwitch) authSwitch.textContent = 'Sign in';
        authSwitchWrapper?.classList.remove('hidden');
    }

    function showForgotPassword() {
        hideAllScreens();
        resetTabs();
        forgotForm?.classList.remove('hidden');
        authSwitchWrapper?.classList.add('hidden');
    }

    function showVerificationCode() {
        hideAllScreens();
        resetTabs();
        verificationForm?.classList.remove('hidden');
        authSwitchWrapper?.classList.add('hidden');
        document.getElementById('verificationCode')?.focus();
    }

    function showResetPassword() {
        hideAllScreens();
        resetTabs();
        resetPasswordForm?.classList.remove('hidden');
        authSwitchWrapper?.classList.add('hidden');
        document.getElementById('resetPassword')?.focus();
    }

    function showPasswordResetSuccess() {
        hideAllScreens();
        resetTabs();
        passwordResetSuccess?.classList.remove('hidden');
        authSwitchWrapper?.classList.add('hidden');

        if (window.lucide) window.lucide.createIcons();
    }

    // Open login/register from all matching buttons
    document.querySelectorAll(
        '#loginButton, #homeLoginButton, [data-auth="login"], [data-open-modal]:not([data-open-join])'
    ).forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            setMobileMenu(false);
            openModal();
            showLogin();
        });
    });

    document.querySelectorAll(
        '#registerButton, #joinButton, #homeRegisterButton, [data-auth="register"], [data-open-join]'
    ).forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            setMobileMenu(false);
            openModal();
            showRegister();
        });
    });

    loginTab?.addEventListener('click', function (event) {
        event.preventDefault();
        showLogin();
    });

    joinTab?.addEventListener('click', function (event) {
        event.preventDefault();
        showRegister();
    });

    authSwitch?.addEventListener('click', function (event) {
        event.preventDefault();

        if (joinForm && !joinForm.classList.contains('hidden')) {
            showLogin();
        } else {
            showRegister();
        }
    });

    forgotPasswordButton?.addEventListener('click', function (event) {
        event.preventDefault();
        showForgotPassword();
    });

    forgotBackButton?.addEventListener('click', function (event) {
        event.preventDefault();
        showLogin();
    });

    verificationBackButton?.addEventListener('click', function (event) {
        event.preventDefault();
        showForgotPassword();
    });

    loginNowButton?.addEventListener('click', function (event) {
        event.preventDefault();
        openModal();
        showLogin();
        loginForm?.querySelector('input[name="email"]')?.focus();
    });

    closeModalButton?.addEventListener('click', function (event) {
        event.preventDefault();
        closeModal();
    });

    authModal?.addEventListener('click', function (event) {
        if (event.target === authModal) closeModal();
    });

    // Password visibility toggles
    document.querySelectorAll('[data-password]').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const input = document.getElementById(
                this.getAttribute('data-password')
            );
            const icon = this.querySelector('[data-lucide]');

            if (!input) return;

            input.type = input.type === 'password' ? 'text' : 'password';

            if (icon) {
                icon.setAttribute(
                    'data-lucide',
                    input.type === 'password' ? 'eye' : 'eye-off'
                );
            }

            if (window.lucide) window.lucide.createIcons();
        });
    });

    // OTP: digits only, maximum six
    const verificationCode = document.getElementById('verificationCode');

    verificationCode?.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });

    // Password confirmation validation
    function validatePasswords(form, passwordId, confirmationId) {
        form?.addEventListener('submit', function (event) {
            const password = document.getElementById(passwordId);
            const confirmation = document.getElementById(confirmationId);

            if (!password || !confirmation) return;

            if (password.value.length < 8) {
                event.preventDefault();
                alert('Password must be at least 8 characters.');
                password.focus();
                return;
            }

            if (password.value !== confirmation.value) {
                event.preventDefault();
                alert('Passwords do not match.');
                confirmation.focus();
            }
        });
    }

    validatePasswords(joinForm, 'joinPassword', 'joinPasswordConfirmation');
    validatePasswords(
        resetPasswordForm,
        'resetPassword',
        'resetPasswordConfirmation'
    );

    // Loading buttons
    document.querySelectorAll('[data-loading-button]').forEach(button => {
        const form = button.closest('form');
        if (!form) return;

        form.addEventListener('submit', function (event) {
            if (event.defaultPrevented) return;

            button.disabled = true;
            button.classList.add('opacity-80', 'cursor-not-allowed');

            const text = button.querySelector('.button-text');
            const icon = button.querySelector('[data-lucide]');

            if (text) text.textContent = 'Please wait...';

            if (icon) {
                icon.setAttribute('data-lucide', 'loader-circle');
                icon.classList.add('animate-spin');
            }

            if (window.lucide) window.lucide.createIcons();
        });
    });

    // Restore the appropriate auth screen after redirect
    @if (session('open_auth_modal') === 'login')
        openModal();
        showLogin();
    @elseif (session('open_auth_modal') === 'register')
        openModal();
        showRegister();
    @elseif (session('open_auth_modal') === 'forgot')
        openModal();
        showForgotPassword();
    @elseif (session('open_auth_modal') === 'verification')
        openModal();
        showVerificationCode();
    @elseif (session('open_auth_modal') === 'reset-password')
        openModal();
        showResetPassword();
    @elseif (session('open_auth_modal') === 'password-success')
        openModal();
        showPasswordResetSuccess();
    @endif
});
</script>


