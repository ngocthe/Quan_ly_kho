<template>
    <v-container id="login" class="fill-height justify-center" tag="section">
        <v-row justify="center">
            <v-slide-y-transition appear>
                <base-material-card
                    color="success"
                    light
                    max-width="100%"
                    width="400"
                    class="px-5 py-3"
                >
                    <template v-slot:heading>
                        <div class="text-center">
                            <h1 class="display-2 font-weight-bold mb-0">
                                {{ $t("login_with") }}
                            </h1>

                            <v-btn
                                v-for="(social, i) in socials"
                                :key="i"
                                :href="social.href"
                                class="ma-1"
                                icon
                                rel="noopener"
                                target="_blank"
                            >
                                <v-icon v-text="social.icon" />
                            </v-btn>
                        </div>
                    </template>

                    <v-card-text class="text-center">
                        <div
                            class="text-center grey--text body-1 font-weight-light"
                        >
                            {{ $t("login_description") }}
                        </div>
                        <v-form ref="form" lazy-validation v-model="valid">
                            <v-text-field
                                color="secondary"
                                v-model="form.username"
                                ref="login"
                                @keyup.enter="login"
                                :disabled="loading"
                                :label="$t('login_username')"
                                :rules="rule.nameRules"
                                prepend-icon="mdi-account"
                                class="mt-10"
                            />

                            <v-text-field
                                class="mb-8"
                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="show = !show"
                                color="secondary"
                                @keyup.enter="login"
                                v-model="form.password"
                                :disabled="loading"
                                :rules="rule.passwordRules"
                                :label="$t('login_password')"
                                prepend-icon="mdi-lock-outline"
                                :type="show ? 'text' : 'password'"
                            />
                        </v-form>

                        <pages-btn
                            large
                            color
                            depressed
                            @click="login"
                            :loading="loading"
                            class="v-btn--text success--text"
                            >{{ $t("login_button_text") }}</pages-btn
                        >
                    </v-card-text>
                </base-material-card>
            </v-slide-y-transition>
        </v-row>
        <!-- <v-snackbar
            v-model="snackbar"
            :bottom="y === 'bottom'"
            :color="color"
            :left="x === 'left'"
            :multi-line="mode === 'multi-line'"
            :right="x === 'right'"
            :timeout="timeout"
            :top="y === 'top'"
            :vertical="mode === 'vertical'"
        >
            {{ text }}
        </v-snackbar>-->
    </v-container>
</template>

<script>
export default {
    name: "PagesLogin",

    components: {
        PagesBtn: () => import("@/layouts/pages/components/Btn")
    },

    data: () => ({
        valid: true,
        show: false,
        loading: false,
        form: {
            username: localStorage.getItem("username"),
            password: localStorage.getItem("password")
        },
        rule: {
            nameRules: [v => !!v || "Bạn chưa nhập tên đăng nhập"],
            passwordRules: [v => !!v || "Bạn chưa nhập password"]
        },
        socials: [
            {
                href: "#",
                icon: "mdi-facebook"
            },
            {
                href: "#",
                icon: "mdi-twitter"
            },
            {
                href: "#",
                icon: "mdi-github"
            }
        ]
    }),
    methods: {
        login() {
            this.$refs.form.validate();
            if (!this.valid) return;
            this.loading = true;
            this.$store
                .dispatch("user/login", this.form)
                .then(homeUrl => {
                    this.loading = false;
                    localStorage.setItem("username", this.form.username);
                    localStorage.setItem("password", this.form.password);
                    window.location.reload();
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 401) {
                        this.$snackbar("Sai tài khoản hoặc mật khẩu", "error");
                        this.loading = false;
                    }
                });
        }
    },
    mounted() {
        if (this.form.username) this.$refs.login.focus();
    }
};
</script>
