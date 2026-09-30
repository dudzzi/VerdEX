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

    }
);