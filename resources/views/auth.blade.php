<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
            position: relative;
            overflow: hidden;
            width: 820px;
            max-width: 98vw;
            min-height: 560px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            overflow-y: auto;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100%  { opacity: 1; z-index: 5; }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: linear-gradient(135deg, #FF4B2B, #FF416C);
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .overlay-panel h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .overlay-panel p {
            font-size: 13px;
            font-weight: 300;
            line-height: 1.7;
            margin-bottom: 28px;
            opacity: 0.9;
        }

        .ghost-btn {
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            padding: 11px 38px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.25s, color 0.25s;
            font-family: 'Poppins', sans-serif;
        }

        .ghost-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        form {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 40px 44px;
            height: 100%;
            text-align: center;
        }

        form h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        form .tagline {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 22px;
        }

        .input-group {
            width: 100%;
            margin-bottom: 4px;
            text-align: left;
        }

        .input-wrap {
            position: relative;
            width: 100%;
        }

        .input-wrap input {
            width: 100%;
            padding: 11px 14px 11px 38px;
            background: #f4f5f7;
            border: 1.5px solid #e8eaed;
            border-radius: 8px;
            font-size: 13px;
            color: #333;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-wrap input:focus {
            border-color: #FF4B2B;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255,75,43,0.1);
        }

        .input-wrap input.inp-error {
            border-color: #e53935;
            box-shadow: 0 0 0 3px rgba(229,57,53,0.1);
        }

        .input-wrap input.inp-valid {
            border-color: #43a047;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            pointer-events: none;
        }

        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            user-select: none;
        }

        .err-msg {
            font-size: 11px;
            color: #e53935;
            margin: 3px 0 6px 2px;
            display: flex;
            align-items: center;
            gap: 3px;
            min-height: 16px;
        }

        .server-errors {
            background: #fff0f0;
            border: 1px solid #ffcdd2;
            border-radius: 8px;
            padding: 9px 13px;
            width: 100%;
            margin-bottom: 14px;
            text-align: left;
        }

        .server-errors p {
            font-size: 11.5px;
            color: #c62828;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .server-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 9px 13px;
            width: 100%;
            margin-bottom: 14px;
            text-align: left;
        }

        .server-success p {
            font-size: 11.5px;
            color: #166534;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pass-meter {
            width: 100%;
            margin: 4px 0 6px;
        }

        .meter-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 3px;
        }

        .meter-bar {
            flex: 1;
            height: 3px;
            border-radius: 3px;
            background: #e8eaed;
            transition: background 0.3s;
        }

        .meter-label {
            font-size: 10.5px;
            font-weight: 500;
        }

        .rules-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 10px;
            width: 100%;
            margin-bottom: 8px;
        }

        .rule-item {
            font-size: 10.5px;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color 0.2s;
        }

        .rule-item.ok { color: #43a047; }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #FF4B2B, #FF416C);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: opacity 0.2s, transform 0.15s;
            margin-top: 4px;
        }

        .submit-btn:hover { opacity: 0.92; transform: translateY(-1px); }
        .submit-btn:active { transform: scale(0.98); }
        .submit-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        .forgot-link {
            font-size: 12px;
            color: #FF4B2B;
            text-decoration: none;
            margin-bottom: 14px;
            align-self: flex-end;
        }

        @media (max-width: 640px) {
            .container { flex-direction: column; min-height: unset; }
            .form-container, .overlay-container { position: relative; width: 100%; height: auto; }
            .sign-up-container, .sign-in-container { position: relative; opacity: 1; transform: none !important; }
            .overlay-container { display: none; }
        }
    </style>
</head>
<body>

<div id="app">
    <div :class="['container', { 'right-panel-active': isSignUp }]">

        <div class="form-container sign-up-container">
            <form @submit.prevent="submitRegister" novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="form_type" value="register">
                <h1>Create Account</h1>
                <p class="tagline">Use your email for registration</p>

                <div v-if="serverRegisterErrors.length" class="server-errors">
                    <p v-for="e in serverRegisterErrors" :key="e">⚠ @{{ e }}</p>
                </div>

                <div class="input-group">
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input
                            type="text"
                            v-model="reg.name"
                            placeholder="Full Name"
                            :class="{ 'inp-error': regErr.name, 'inp-valid': reg.name && !regErr.name }"
                            @blur="validateRegName"
                        >
                    </div>
                    <div class="err-msg" v-if="regErr.name">⚠ @{{ regErr.name }}</div>
                    <div class="err-msg" v-else></div>
                </div>

                <div class="input-group">
                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input
                            type="email"
                            v-model="reg.email"
                            placeholder="Email Address"
                            :class="{ 'inp-error': regErr.email, 'inp-valid': reg.email && !regErr.email && !checkingEmail }"
                            @blur="validateRegEmail"
                        >
                        <span class="toggle-eye" v-if="checkingEmail" style="font-size:12px;color:#aaa">⏳</span>
                    </div>
                    <div class="err-msg" v-if="regErr.email">⚠ @{{ regErr.email }}</div>
                    <div class="err-msg" v-else-if="checkingEmail" style="color:#888">Checking email...</div>
                    <div class="err-msg" v-else></div>
                </div>

                <div class="input-group">
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            :type="showRegPass ? 'text' : 'password'"
                            v-model="reg.password"
                            placeholder="Create Password"
                            :class="{ 'inp-error': regErr.password, 'inp-valid': passScore === 5 }"
                            @input="onPassInput"
                            @blur="validateRegPassword"
                        >
                        <span class="toggle-eye" @click="showRegPass = !showRegPass">
                            @{{ showRegPass ? '🙈' : '👁' }}
                        </span>
                    </div>
                    <div class="err-msg" v-if="regErr.password">⚠ @{{ regErr.password }}</div>

                    <div class="pass-meter" v-if="reg.password">
                        <div class="meter-bars">
                            <div class="meter-bar" v-for="i in 4" :key="i"
                                :style="{ background: i <= passScore ? meterColor : '#e8eaed' }"></div>
                        </div>
                        <span class="meter-label" :style="{ color: meterColor }">@{{ meterText }}</span>
                    </div>

                    <div class="rules-grid" v-if="reg.password">
                        <div :class="['rule-item', { ok: passRules.len }]">@{{ passRules.len ? '✔' : '✖' }} Min 8 chars</div>
                        <div :class="['rule-item', { ok: passRules.upper }]">@{{ passRules.upper ? '✔' : '✖' }} Uppercase</div>
                        <div :class="['rule-item', { ok: passRules.lower }]">@{{ passRules.lower ? '✔' : '✖' }} Lowercase</div>
                        <div :class="['rule-item', { ok: passRules.num }]">@{{ passRules.num ? '✔' : '✖' }} Number</div>
                        <div :class="['rule-item', { ok: passRules.special }]">@{{ passRules.special ? '✔' : '✖' }} Special char</div>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrap">
                        <span class="input-icon">🛡</span>
                        <input
                            :type="showConfPass ? 'text' : 'password'"
                            v-model="reg.password_confirmation"
                            placeholder="Confirm Password"
                            :class="{ 'inp-error': regErr.confirm, 'inp-valid': reg.password_confirmation && !regErr.confirm }"
                            @input="validateConfirm"
                            @blur="validateConfirm"
                        >
                        <span class="toggle-eye" @click="showConfPass = !showConfPass">
                            @{{ showConfPass ? '🙈' : '👁' }}
                        </span>
                    </div>
                    <div class="err-msg" v-if="regErr.confirm">⚠ @{{ regErr.confirm }}</div>
                    <div class="err-msg" v-else-if="reg.password_confirmation && !regErr.confirm" style="color:#43a047">✔ Passwords match</div>
                    <div class="err-msg" v-else></div>
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form @submit.prevent="submitLogin" novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="form_type" value="login">
                <h1>Sign In</h1>
                <p class="tagline">Use your account credentials</p>

                <div v-if="serverLoginError" class="server-errors">
                    <p>⚠ @{{ serverLoginError }}</p>
                </div>

                <div v-if="serverSuccess" class="server-success">
                    <p>✔ @{{ serverSuccess }}</p>
                </div>

                <div class="input-group">
                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input
                            type="email"
                            v-model="login.email"
                            placeholder="Email Address"
                            :class="{ 'inp-error': loginErr.email, 'inp-valid': login.email && !loginErr.email }"
                            @blur="validateLoginEmail"
                        >
                    </div>
                    <div class="err-msg" v-if="loginErr.email">⚠ @{{ loginErr.email }}</div>
                    <div class="err-msg" v-else></div>
                </div>

                <div class="input-group">
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            :type="showLoginPass ? 'text' : 'password'"
                            v-model="login.password"
                            placeholder="Password"
                            :class="{ 'inp-error': loginErr.password }"
                            @blur="validateLoginPassword"
                        >
                        <span class="toggle-eye" @click="showLoginPass = !showLoginPass">
                            @{{ showLoginPass ? '🙈' : '👁' }}
                        </span>
                    </div>
                    <div class="err-msg" v-if="loginErr.password">⚠ @{{ loginErr.password }}</div>
                    <div class="err-msg" v-else></div>
                </div>

                <!-- <a href="#" class="forgot-link">Forgot your password?</a> -->

                <button type="submit" class="submit-btn">Sign In</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost-btn" @click="switchTo('login')">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost-btn" @click="switchTo('register')">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const { createApp } = Vue;

    const serverErrors    = @json($errors->all());
    const formType        = "{{ old('form_type') }}";
    const serverSuccess   = "{{ session('success') }}";
    const serverLoginErr  = "{{ session('error') }}";
    const oldEmail        = "{{ old('email') }}";
    const oldName         = "{{ old('name') }}";

    createApp({
        data() {
            return {
                isSignUp: formType === 'register' && serverErrors.length > 0,

                login: {
                    email: formType === 'login' ? oldEmail : '',
                    password: '',
                },
                loginErr: { email: '', password: '' },
                showLoginPass: false,
                serverLoginError: formType === 'login' && serverErrors.length ? serverErrors[0] : (serverLoginErr || ''),
                serverSuccess: serverSuccess || '',

                reg: {
                    name: formType === 'register' ? oldName : '',
                    email: formType === 'register' ? oldEmail : '',
                    password: '',
                    password_confirmation: '',
                },
                regErr: { name: '', email: '', password: '', confirm: '' },
                showRegPass: false,
                showConfPass: false,
                serverRegisterErrors: formType === 'register' ? serverErrors : [],
                checkingEmail: false,

                passRules: { len: false, upper: false, lower: false, num: false, special: false },
                passScore: 0,
                meterColor: '#e8eaed',
                meterText: '',
            };
        },

        methods: {
            switchTo(panel) {
                this.isSignUp = panel === 'register';
            },

            validateLoginEmail() {
                const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.login.email) {
                    this.loginErr.email = 'Email is required.';
                } else if (!emailRe.test(this.login.email)) {
                    this.loginErr.email = 'Enter a valid email address.';
                } else {
                    this.loginErr.email = '';
                }
                return !this.loginErr.email;
            },

            validateLoginPassword() {
                if (!this.login.password) {
                    this.loginErr.password = 'Password is required.';
                } else {
                    this.loginErr.password = '';
                }
                return !this.loginErr.password;
            },

            submitLogin() {
                this.serverLoginError = '';
                const emailOk = this.validateLoginEmail();
                const passOk  = this.validateLoginPassword();
                if (!emailOk || !passOk) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/login';

                const fields = {
                    _token:    document.querySelector('input[name="_token"]').value,
                    form_type: 'login',
                    email:     this.login.email,
                    password:  this.login.password,
                };

                Object.entries(fields).forEach(([k, v]) => {
                    const inp = document.createElement('input');
                    inp.type  = 'hidden';
                    inp.name  = k;
                    inp.value = v;
                    form.appendChild(inp);
                });

                document.body.appendChild(form);
                form.submit();
            },

            validateRegName() {
                if (!this.reg.name.trim()) {
                    this.regErr.name = 'Name is required.';
                } else if (this.reg.name.trim().length < 3) {
                    this.regErr.name = 'Name must be at least 3 characters.';
                } else {
                    this.regErr.name = '';
                }
                return !this.regErr.name;
            },

            async validateRegEmail() {
                const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.reg.email) {
                    this.regErr.email = 'Email is required.';
                    return false;
                }
                if (!emailRe.test(this.reg.email)) {
                    this.regErr.email = 'Enter a valid email address.';
                    return false;
                }
                this.checkingEmail = true;
                this.regErr.email = '';
                try {
                    const res  = await fetch('/check-email?email=' + encodeURIComponent(this.reg.email));
                    const data = await res.json();
                    if (data.exists) {
                        this.regErr.email = 'This email is already registered.';
                        this.checkingEmail = false;
                        return false;
                    }
                } catch (err) {}
                this.regErr.email  = '';
                this.checkingEmail = false;
                return true;
            },

            validateRegPassword() {
                if (!this.reg.password) {
                    this.regErr.password = 'Password is required.';
                } else if (this.passScore < 5) {
                    this.regErr.password = 'Password does not meet all requirements.';
                } else {
                    this.regErr.password = '';
                }
                return !this.regErr.password;
            },

            validateConfirm() {
                if (!this.reg.password_confirmation) {
                    this.regErr.confirm = 'Please confirm your password.';
                } else if (this.reg.password_confirmation !== this.reg.password) {
                    this.regErr.confirm = 'Passwords do not match.';
                } else {
                    this.regErr.confirm = '';
                }
                return !this.regErr.confirm;
            },

            onPassInput() {
                const v = this.reg.password;
                this.passRules.len     = v.length >= 8;
                this.passRules.upper   = /[A-Z]/.test(v);
                this.passRules.lower   = /[a-z]/.test(v);
                this.passRules.num     = /[0-9]/.test(v);
                this.passRules.special = /[@$!%*#?&^()\-_+=]/.test(v);

                this.passScore = Object.values(this.passRules).filter(Boolean).length;

                const colors = { 1: '#e53935', 2: '#fb8c00', 3: '#fdd835', 4: '#66bb6a', 5: '#43a047' };
                const labels = { 1: 'Very weak', 2: 'Weak', 3: 'Fair', 4: 'Strong', 5: 'Very strong' };

                this.meterColor = colors[this.passScore] || '#e8eaed';
                this.meterText  = labels[this.passScore] || '';

                if (this.reg.password_confirmation) this.validateConfirm();
                if (this.regErr.password) this.validateRegPassword();
            },

            async submitRegister() {
                this.serverRegisterErrors = [];
                const n = this.validateRegName();
                const e = await this.validateRegEmail();
                const p = this.validateRegPassword();
                const c = this.validateConfirm();
                if (!n || !e || !p || !c) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/register';

                const fields = {
                    _token:                document.querySelector('input[name="_token"]').value,
                    form_type:             'register',
                    name:                  this.reg.name,
                    email:                 this.reg.email,
                    password:              this.reg.password,
                    password_confirmation: this.reg.password_confirmation,
                };

                Object.entries(fields).forEach(([k, v]) => {
                    const inp = document.createElement('input');
                    inp.type  = 'hidden';
                    inp.name  = k;
                    inp.value = v;
                    form.appendChild(inp);
                });

                document.body.appendChild(form);
                form.submit();
            },
        },
    }).mount('#app');
</script>

</body>
</html>