document.addEventListener(
    "DOMContentLoaded",
    function () {

        /* =========================================
           ELEMENTS
           ========================================= */

        const ownerToggle =
            document.getElementById(
                "ownerToggle"
            );

        const helperToggle =
            document.getElementById(
                "helperToggle"
            );

        const ownerSection =
            document.getElementById(
                "ownerLoginSection"
            );

        const helperSection =
            document.getElementById(
                "helperLoginSection"
            );

        const ownerUsername =
            document.getElementById(
                "ownerUsername"
            );

        const searchOwnerButton =
            document.getElementById(
                "searchOwnerButton"
            );

        const profileResults =
            document.getElementById(
                "helperProfileResults"
            );


        /* =========================================
           HELPER PIN MODAL ELEMENTS
           ========================================= */

        const helperPinOverlay =
            document.getElementById(
                "helperPinOverlay"
            );

        const helperPinName =
            document.getElementById(
                "helperPinName"
            );

        const helperPinAvatar =
            document.getElementById(
                "helperPinAvatar"
            );

        const selectedHelperId =
            document.getElementById(
                "selectedHelperId"
            );

        const helperLoginPin =
            document.getElementById(
                "helperLoginPin"
            );

        const helperPinMessage =
            document.getElementById(
                "helperPinMessage"
            );

        const closeHelperPin =
            document.getElementById(
                "closeHelperPin"
            );

        const helperPinContinue =
            document.getElementById(
                "helperPinContinue"
            );


        /* =========================================
           OWNER TOGGLE
           ========================================= */

        ownerToggle.addEventListener(
            "click",
            function () {

                ownerToggle.classList.add(
                    "active"
                );

                helperToggle.classList.remove(
                    "active"
                );

                ownerSection.classList.remove(
                    "hidden"
                );

                helperSection.classList.add(
                    "hidden"
                );

            }
        );


        /* =========================================
           HELPER TOGGLE
           ========================================= */

        helperToggle.addEventListener(
            "click",
            function () {

                helperToggle.classList.add(
                    "active"
                );

                ownerToggle.classList.remove(
                    "active"
                );

                helperSection.classList.remove(
                    "hidden"
                );

                ownerSection.classList.add(
                    "hidden"
                );

                ownerUsername.focus();

            }
        );


        /* =========================================
           SEARCH HELPER PROFILES
           ========================================= */

        async function searchHelperProfiles() {

            const username =
                ownerUsername.value.trim();


            if (username === "") {

                showMessage(
                    "Please enter the owner's username.",
                    "error"
                );

                return;
            }


            searchOwnerButton.disabled = true;

            searchOwnerButton.textContent =
                "Searching...";


            profileResults.innerHTML = `
                <p class="helper-empty-message">
                    Searching for helper profiles...
                </p>
            `;


            try {

                const formData =
                    new FormData();

                formData.append(
                    "owner_username",
                    username
                );


                const response =
                    await fetch(
                        "../api/search-helper-profiles.php",
                        {
                            method: "POST",
                            body: formData
                        }
                    );


                const data =
                    await response.json();


                if (!data.success) {

                    showMessage(
                        data.message,
                        "error"
                    );

                    return;
                }


                if (
                    !Array.isArray(
                        data.profiles
                    ) ||
                    data.profiles.length === 0
                ) {

                    showMessage(
                        data.owner.full_name +
                        " has no helper profiles yet.",
                        "empty"
                    );

                    return;
                }


                displayProfiles(
                    data.owner,
                    data.profiles
                );


            } catch (error) {

                console.error(
                    "Helper search error:",
                    error
                );


                showMessage(
                    "Unable to search for helper profiles.",
                    "error"
                );

            } finally {

                searchOwnerButton.disabled = false;

                searchOwnerButton.textContent =
                    "Search";

            }

        }


        /* =========================================
           DISPLAY PROFILES
           ========================================= */

        function displayProfiles(
            owner,
            profiles
        ) {

            profileResults.innerHTML = "";


            const wrapper =
                document.createElement(
                    "div"
                );

            wrapper.className =
                "helper-profile-wrapper";


            const heading =
                document.createElement(
                    "div"
                );

            heading.className =
                "helper-profile-owner";


            const ownerName =
                document.createElement(
                    "strong"
                );

            ownerName.textContent =
                owner.full_name;


            const instruction =
                document.createElement(
                    "span"
                );

            instruction.textContent =
                "Choose your helper profile";


            heading.appendChild(
                ownerName
            );

            heading.appendChild(
                instruction
            );


            const grid =
                document.createElement(
                    "div"
                );

            grid.className =
                "helper-profile-grid";


            profiles.forEach(
                function (profile) {

                    const button =
                        document.createElement(
                            "button"
                        );

                    button.type =
                        "button";

                    button.className =
                        "helper-profile-card";

                    button.dataset.profileId =
                        profile.id;


                    const avatar =
                        document.createElement(
                            "div"
                        );

                    avatar.className =
                        "helper-profile-avatar";


                    const initial =
                        profile.profile_name
                            .charAt(0)
                            .toUpperCase();

                    avatar.textContent =
                        initial;


                    const name =
                        document.createElement(
                            "span"
                        );

                    name.className =
                        "helper-profile-name";

                    name.textContent =
                        profile.profile_name;


                    button.appendChild(
                        avatar
                    );

                    button.appendChild(
                        name
                    );


                    /* =========================================
                       OPEN PIN MODAL
                       ========================================= */

                    button.addEventListener(
                        "click",
                        function () {

                            openHelperPinModal(
                                profile.id,
                                profile.profile_name
                            );

                        }
                    );


                    grid.appendChild(
                        button
                    );

                }
            );


            wrapper.appendChild(
                heading
            );

            wrapper.appendChild(
                grid
            );


            profileResults.appendChild(
                wrapper
            );

        }


        /* =========================================
           PROFILE SEARCH MESSAGE
           ========================================= */

        function showMessage(
            message,
            type
        ) {

            profileResults.innerHTML = "";


            const messageElement =
                document.createElement(
                    "p"
                );

            messageElement.className =
                "helper-search-message";


            if (type === "error") {

                messageElement.classList.add(
                    "error"
                );

            }


            messageElement.textContent =
                message;


            profileResults.appendChild(
                messageElement
            );

        }


        /* =========================================
           HELPER PIN MODAL
           ========================================= */

        function openHelperPinModal(
            profileId,
            profileName
        ) {

            if (!helperPinOverlay) {
                return;
            }


            selectedHelperId.value =
                profileId;


            helperPinName.textContent =
                profileName;


            helperPinAvatar.textContent =
                profileName
                    .charAt(0)
                    .toUpperCase();


            helperLoginPin.value = "";


            helperPinMessage.textContent = "";

            helperPinMessage.classList.remove(
                "error"
            );

            helperPinMessage.style.display =
                "none";


            helperPinOverlay.classList.add(
                "show"
            );


            setTimeout(
                function () {

                    helperLoginPin.focus();

                },
                100
            );

        }


        function hideHelperPinModal() {

            if (!helperPinOverlay) {
                return;
            }


            helperPinOverlay.classList.remove(
                "show"
            );


            helperLoginPin.value = "";

        }


        /* =========================================
           CLOSE PIN MODAL
           ========================================= */

        if (closeHelperPin) {

            closeHelperPin.addEventListener(
                "click",
                hideHelperPinModal
            );

        }


        if (helperPinOverlay) {

            helperPinOverlay.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        helperPinOverlay
                    ) {

                        hideHelperPinModal();

                    }

                }
            );

        }


        /* =========================================
           CONTINUE BUTTON
           ========================================= */

        if (helperPinContinue) {

            helperPinContinue.addEventListener(
                "click",
                function () {

                    const pin =
                        helperLoginPin.value.trim();


                    if (
                        !/^\d{4}$/.test(pin)
                    ) {

                        showPinMessage(
                            "Please enter your 4-digit PIN."
                        );

                        return;
                    }


                    /*
                    PIN verification will be
                    connected in the next step.
                    */

                    console.log(
                        "Selected helper:",
                        selectedHelperId.value
                    );

                    console.log(
                        "PIN entered:",
                        pin
                    );

                }
            );

        }


        /* =========================================
           PIN MESSAGE
           ========================================= */

        function showPinMessage(
            message
        ) {

            if (!helperPinMessage) {
                return;
            }


            helperPinMessage.textContent =
                message;


            helperPinMessage.classList.add(
                "error"
            );


            helperPinMessage.style.display =
                "block";

        }


        /* =========================================
           SEARCH BUTTON
           ========================================= */

        searchOwnerButton.addEventListener(
            "click",
            searchHelperProfiles
        );


        /* =========================================
           ENTER KEY - OWNER SEARCH
           ========================================= */

        ownerUsername.addEventListener(
            "keydown",
            function (event) {

                if (event.key === "Enter") {

                    event.preventDefault();

                    searchHelperProfiles();

                }

            }
        );


        /* =========================================
           ENTER KEY - PIN
           ========================================= */

        if (helperLoginPin) {

            helperLoginPin.addEventListener(
                "keydown",
                function (event) {

                    if (
                        event.key === "Enter"
                    ) {

                        event.preventDefault();

                        helperPinContinue.click();

                    }

                }
            );

        }

    }
);