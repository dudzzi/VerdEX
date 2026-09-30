document.addEventListener(
    "DOMContentLoaded",
    function () {

        const profileButtons =
            document.querySelectorAll(
                ".profile-menu-toggle"
            );

        const profilePopup =
            document.getElementById(
                "profilePopup"
            );

        if (!profilePopup) {
            return;
        }


        profileButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function (event) {

                        event.preventDefault();
                        event.stopPropagation();

                        profilePopup.classList.toggle(
                            "show"
                        );

                    }
                );

            }
        );


        profilePopup.addEventListener(
            "click",
            function (event) {
                event.stopPropagation();
            }
        );


        document.addEventListener(
            "click",
            function () {

                profilePopup.classList.remove(
                    "show"
                );

            }
        );


        document.addEventListener(
            "keydown",
            function (event) {

                if (event.key === "Escape") {

                    profilePopup.classList.remove(
                        "show"
                    );

                }

            }
        );
        /* =========================================
        CREATE HELPER PROFILE MODAL
        ========================================= */

        const createHelperButton =
            document.getElementById(
                "createHelperProfileButton"
            );

        const helperModalOverlay =
            document.getElementById(
                "helperModalOverlay"
            );

        const closeHelperModal =
            document.getElementById(
                "closeHelperModal"
            );

        const cancelHelperModal =
            document.getElementById(
                "cancelHelperModal"
            );


        function openHelperModal() {

            if (!helperModalOverlay) {
                return;
            }

            profilePopup.classList.remove(
                "show"
            );

            helperModalOverlay.classList.add(
                "show"
            );

        }


        function hideHelperModal() {

            if (!helperModalOverlay) {
                return;
            }

            helperModalOverlay.classList.remove(
                "show"
            );

        }


        if (createHelperButton) {

            createHelperButton.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    openHelperModal();

                }
            );

        }


        if (closeHelperModal) {

            closeHelperModal.addEventListener(
                "click",
                hideHelperModal
            );

        }


        if (cancelHelperModal) {

            cancelHelperModal.addEventListener(
                "click",
                hideHelperModal
            );

        }


        if (helperModalOverlay) {

            helperModalOverlay.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        helperModalOverlay
                    ) {

                        hideHelperModal();

                    }

                }
            );

        }
        /* =========================================
        SAVE HELPER PROFILE
        ========================================= */

        const createHelperForm =
            document.getElementById(
                "createHelperForm"
            );

        const helperModalMessage =
            document.getElementById(
                "helperModalMessage"
            );


        if (createHelperForm) {

            createHelperForm.addEventListener(
                "submit",
                async function (event) {

                    event.preventDefault();


                    const profileName =
                        document.getElementById(
                            "helperName"
                        ).value.trim();

                    const pin =
                        document.getElementById(
                            "helperPin"
                        ).value.trim();

                    const confirmPin =
                        document.getElementById(
                            "confirmHelperPin"
                        ).value.trim();


                    /* BASIC CHECK */

                    if (
                        profileName === "" ||
                        pin === "" ||
                        confirmPin === ""
                    ) {

                        showHelperMessage(
                            "Please complete all fields.",
                            "error"
                        );

                        return;
                    }


                    /* PIN CHECK */

                    if (!/^\d{4}$/.test(pin)) {

                        showHelperMessage(
                            "PIN must contain exactly 4 numbers.",
                            "error"
                        );

                        return;
                    }


                    if (pin !== confirmPin) {

                        showHelperMessage(
                            "PINs do not match.",
                            "error"
                        );

                        return;
                    }


                    const submitButton =
                        createHelperForm.querySelector(
                            ".helper-create-button"
                        );


                    submitButton.disabled = true;

                    submitButton.textContent =
                        "Creating...";


                    const formData =
                        new FormData(
                            createHelperForm
                        );


                    try {

                        const response =
                            await fetch(
                                "../api/create-helper-profile.php",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            );


                        const data =
                            await response.json();


                        if (!data.success) {

                            showHelperMessage(
                                data.message,
                                "error"
                            );

                            return;
                        }


                        showHelperMessage(
                            data.message,
                            "success"
                        );


                        createHelperForm.reset();


                    } catch (error) {

                        console.error(
                            "Create helper error:",
                            error
                        );


                        showHelperMessage(
                            "Unable to create helper profile.",
                            "error"
                        );

                    } finally {

                        submitButton.disabled = false;

                        submitButton.textContent =
                            "Create Profile";

                    }

                }
            );

        }


        /* =========================================
        HELPER MODAL MESSAGE
        ========================================= */

        function showHelperMessage(
            message,
            type
        ) {

            if (!helperModalMessage) {
                return;
            }


            helperModalMessage.textContent =
                message;


            helperModalMessage.classList.remove(
                "error",
                "success"
            );


            helperModalMessage.classList.add(
                type
            );


            helperModalMessage.style.display =
                "block";

        }
    }
    
);