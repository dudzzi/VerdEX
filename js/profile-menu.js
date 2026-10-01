document.addEventListener(
    "DOMContentLoaded",
    function () {

        /* =========================================
           PROFILE POPUP ELEMENTS
           ========================================= */

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


        /* =========================================
           CREATE HELPER ELEMENTS
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

        const createHelperForm =
            document.getElementById(
                "createHelperForm"
            );

        const helperModalMessage =
            document.getElementById(
                "helperModalMessage"
            );


        /* =========================================
           MANAGE HELPERS ELEMENTS
           ========================================= */

        const manageHelperButton =
            document.getElementById(
                "manageHelperProfilesButton"
            );

        const manageHelperOverlay =
            document.getElementById(
                "manageHelperOverlay"
            );

        const closeManageHelper =
            document.getElementById(
                "closeManageHelper"
            );

        const manageHelperList =
            document.getElementById(
                "manageHelperList"
            );

        const addHelperFromManage =
            document.getElementById(
                "addHelperFromManage"
            );


        /* =========================================
           EDIT HELPER ELEMENTS
           ========================================= */

        const editHelperOverlay =
            document.getElementById(
                "editHelperOverlay"
            );

        const closeEditHelper =
            document.getElementById(
                "closeEditHelper"
            );

        const cancelEditHelper =
            document.getElementById(
                "cancelEditHelper"
            );

        const editHelperForm =
            document.getElementById(
                "editHelperForm"
            );

        const editHelperId =
            document.getElementById(
                "editHelperId"
            );

        const editHelperName =
            document.getElementById(
                "editHelperName"
            );

        const editHelperMessage =
            document.getElementById(
                "editHelperMessage"
            );


        /* =========================================
           CHANGE PIN ELEMENTS
           ========================================= */

        const changePinOverlay =
            document.getElementById(
                "changePinOverlay"
            );

        const closeChangePin =
            document.getElementById(
                "closeChangePin"
            );

        const cancelChangePin =
            document.getElementById(
                "cancelChangePin"
            );

        const changePinForm =
            document.getElementById(
                "changePinForm"
            );

        const changePinHelperId =
            document.getElementById(
                "changePinHelperId"
            );

        const newHelperPin =
            document.getElementById(
                "newHelperPin"
            );

        const confirmNewHelperPin =
            document.getElementById(
                "confirmNewHelperPin"
            );

        const changePinMessage =
            document.getElementById(
                "changePinMessage"
            );


        /* =========================================
           PROFILE POPUP
           ========================================= */

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

                    hideHelperModal();

                    hideManageHelperModal();

                    hideEditHelperModal();

                    hideChangePinModal();

                }

            }
        );


        /* =========================================
           CREATE HELPER MODAL
           ========================================= */

        function openHelperModal() {

            if (!helperModalOverlay) {
                return;
            }


            profilePopup.classList.remove(
                "show"
            );


            if (manageHelperOverlay) {

                manageHelperOverlay.classList.remove(
                    "show"
                );

            }


            if (helperModalMessage) {

                helperModalMessage.textContent =
                    "";

                helperModalMessage.classList.remove(
                    "error",
                    "success"
                );

                helperModalMessage.style.display =
                    "none";

            }


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
           SAVE NEW HELPER PROFILE
           ========================================= */

        if (createHelperForm) {

            createHelperForm.addEventListener(
                "submit",
                async function (event) {

                    event.preventDefault();


                    const helperName =
                        document.getElementById(
                            "helperName"
                        );

                    const helperPin =
                        document.getElementById(
                            "helperPin"
                        );

                    const confirmHelperPin =
                        document.getElementById(
                            "confirmHelperPin"
                        );


                    if (
                        !helperName ||
                        !helperPin ||
                        !confirmHelperPin
                    ) {
                        return;
                    }


                    const profileName =
                        helperName.value.trim();

                    const pin =
                        helperPin.value.trim();

                    const confirmPin =
                        confirmHelperPin.value.trim();


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


                    if (submitButton) {

                        submitButton.disabled =
                            true;

                        submitButton.textContent =
                            "Creating...";

                    }


                    try {

                        const formData =
                            new FormData(
                                createHelperForm
                            );


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


                        setTimeout(
                            function () {

                                hideHelperModal();


                                if (
                                    manageHelperOverlay
                                ) {

                                    manageHelperOverlay
                                        .classList
                                        .add(
                                            "show"
                                        );

                                    loadHelperProfiles();

                                }

                            },
                            700
                        );


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

                        if (submitButton) {

                            submitButton.disabled =
                                false;

                            submitButton.textContent =
                                "Create Profile";

                        }

                    }

                }
            );

        }


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


        /* =========================================
           MANAGE HELPER PROFILES
           ========================================= */

        function openManageHelperModal() {

            if (!manageHelperOverlay) {
                return;
            }


            profilePopup.classList.remove(
                "show"
            );


            manageHelperOverlay.classList.add(
                "show"
            );


            loadHelperProfiles();

        }


        function hideManageHelperModal() {

            if (!manageHelperOverlay) {
                return;
            }


            manageHelperOverlay.classList.remove(
                "show"
            );

        }


        if (manageHelperButton) {

            manageHelperButton.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();

                    openManageHelperModal();

                }
            );

        }


        if (closeManageHelper) {

            closeManageHelper.addEventListener(
                "click",
                hideManageHelperModal
            );

        }


        if (manageHelperOverlay) {

            manageHelperOverlay.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        manageHelperOverlay
                    ) {

                        hideManageHelperModal();

                    }

                }
            );

        }


        /* =========================================
           ADD HELPER FROM MANAGE WINDOW
           ========================================= */

        if (addHelperFromManage) {

            addHelperFromManage.addEventListener(
                "click",
                function () {

                    if (manageHelperOverlay) {

                        manageHelperOverlay
                            .classList
                            .remove(
                                "show"
                            );

                    }


                    openHelperModal();

                }
            );

        }


        /* =========================================
           LOAD HELPER PROFILES
           ========================================= */

        async function loadHelperProfiles() {

            if (!manageHelperList) {
                return;
            }


            manageHelperList.innerHTML = `
                <p class="manage-helper-empty">
                    Loading helper profiles...
                </p>
            `;


            try {

                const response =
                    await fetch(
                        "../api/get-helper-profiles.php",
                        {
                            cache: "no-store"
                        }
                    );


                const data =
                    await response.json();


                if (!data.success) {

                    manageHelperList.innerHTML = `
                        <p class="manage-helper-empty">
                            ${escapeHtml(
                                data.message
                            )}
                        </p>
                    `;

                    return;
                }


                if (
                    !Array.isArray(
                        data.profiles
                    ) ||
                    data.profiles.length === 0
                ) {

                    manageHelperList.innerHTML = `
                        <p class="manage-helper-empty">
                            You haven't created any
                            helper profiles yet.
                        </p>
                    `;

                    return;
                }


                manageHelperList.innerHTML =
                    "";


                data.profiles.forEach(
                    function (profile) {

                        const item =
                            document.createElement(
                                "div"
                            );


                        item.className =
                            "manage-helper-item";


                        const initial =
                            profile.profile_name
                                .charAt(0)
                                .toUpperCase();


                        const statusText =
                            profile.is_active
                                ? "Active"
                                : "Disabled";


                        item.innerHTML = `
                            <div class="manage-helper-info">

                                <div class="manage-helper-avatar">
                                    ${escapeHtml(
                                        initial
                                    )}
                                </div>

                                <div class="manage-helper-details">

                                    <strong>
                                        ${escapeHtml(
                                            profile.profile_name
                                        )}
                                    </strong>

                                    <span class="${
                                        profile.is_active
                                            ? "active"
                                            : "disabled"
                                    }">
                                        ${statusText}
                                    </span>

                                </div>

                            </div>


                            <div class="manage-helper-actions">

                                <button
                                    type="button"
                                    class="
                                        helper-action-button
                                        edit-helper-button
                                    "
                                >
                                    Edit
                                </button>


                                <button
                                    type="button"
                                    class="
                                        helper-action-button
                                        change-pin-button
                                    "
                                >
                                    Change PIN
                                </button>

                            </div>
                        `;


                        manageHelperList.appendChild(
                            item
                        );


                        /* EDIT BUTTON */

                        const editButton =
                            item.querySelector(
                                ".edit-helper-button"
                            );


                        if (editButton) {

                            editButton.addEventListener(
                                "click",
                                function () {

                                    openEditHelperModal(
                                        profile.id,
                                        profile.profile_name
                                    );

                                }
                            );

                        }


                        /* CHANGE PIN BUTTON */

                        const changePinButton =
                            item.querySelector(
                                ".change-pin-button"
                            );


                        if (changePinButton) {

                            changePinButton.addEventListener(
                                "click",
                                function () {

                                    openChangePinModal(
                                        profile.id
                                    );

                                }
                            );

                        }

                    }
                );


            } catch (error) {

                console.error(
                    "Load helper profiles error:",
                    error
                );


                manageHelperList.innerHTML = `
                    <p class="manage-helper-empty">
                        Unable to load helper profiles.
                    </p>
                `;

            }

        }


        /* =========================================
           EDIT / RENAME HELPER MODAL
           ========================================= */

        function openEditHelperModal(
            helperId,
            helperName
        ) {

            if (!editHelperOverlay) {
                return;
            }


            if (manageHelperOverlay) {

                manageHelperOverlay.classList.remove(
                    "show"
                );

            }


            editHelperId.value =
                helperId;


            editHelperName.value =
                helperName;


            if (editHelperMessage) {

                editHelperMessage.textContent =
                    "";

                editHelperMessage.classList.remove(
                    "error",
                    "success"
                );

                editHelperMessage.style.display =
                    "none";

            }


            editHelperOverlay.classList.add(
                "show"
            );


            setTimeout(
                function () {

                    editHelperName.focus();

                },
                100
            );

        }


        function hideEditHelperModal() {

            if (!editHelperOverlay) {
                return;
            }


            editHelperOverlay.classList.remove(
                "show"
            );

        }


        if (closeEditHelper) {

            closeEditHelper.addEventListener(
                "click",
                hideEditHelperModal
            );

        }


        if (cancelEditHelper) {

            cancelEditHelper.addEventListener(
                "click",
                hideEditHelperModal
            );

        }


        if (editHelperOverlay) {

            editHelperOverlay.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        editHelperOverlay
                    ) {

                        hideEditHelperModal();

                    }

                }
            );

        }


        /* =========================================
           SAVE EDITED HELPER NAME
           ========================================= */

        if (editHelperForm) {

            editHelperForm.addEventListener(
                "submit",
                async function (event) {

                    event.preventDefault();


                    const helperId =
                        editHelperId.value;

                    const helperName =
                        editHelperName
                            .value
                            .trim();


                    if (
                        helperName.length < 2 ||
                        helperName.length > 100
                    ) {

                        showEditHelperMessage(
                            "Helper name must be between 2 and 100 characters.",
                            "error"
                        );

                        return;
                    }


                    const submitButton =
                        editHelperForm.querySelector(
                            ".helper-create-button"
                        );


                    if (submitButton) {

                        submitButton.disabled =
                            true;

                        submitButton.textContent =
                            "Saving...";

                    }


                    try {

                        const formData =
                            new FormData();


                        formData.append(
                            "helper_id",
                            helperId
                        );


                        formData.append(
                            "profile_name",
                            helperName
                        );


                        const response =
                            await fetch(
                                "../api/update-helper-profile.php",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            );


                        const data =
                            await response.json();


                        if (!data.success) {

                            showEditHelperMessage(
                                data.message,
                                "error"
                            );

                            return;
                        }


                        showEditHelperMessage(
                            data.message,
                            "success"
                        );


                        setTimeout(
                            function () {

                                hideEditHelperModal();


                                if (
                                    manageHelperOverlay
                                ) {

                                    manageHelperOverlay
                                        .classList
                                        .add(
                                            "show"
                                        );


                                    loadHelperProfiles();

                                }

                            },
                            700
                        );


                    } catch (error) {

                        console.error(
                            "Update helper error:",
                            error
                        );


                        showEditHelperMessage(
                            "Unable to update helper profile.",
                            "error"
                        );

                    } finally {

                        if (submitButton) {

                            submitButton.disabled =
                                false;

                            submitButton.textContent =
                                "Save Changes";

                        }

                    }

                }
            );

        }


        function showEditHelperMessage(
            message,
            type
        ) {

            if (!editHelperMessage) {
                return;
            }


            editHelperMessage.textContent =
                message;


            editHelperMessage.classList.remove(
                "error",
                "success"
            );


            editHelperMessage.classList.add(
                type
            );


            editHelperMessage.style.display =
                "block";

        }


        /* =========================================
           CHANGE PIN MODAL
           ========================================= */

        function openChangePinModal(
            helperId
        ) {

            if (!changePinOverlay) {
                return;
            }


            if (manageHelperOverlay) {

                manageHelperOverlay.classList.remove(
                    "show"
                );

            }


            changePinHelperId.value =
                helperId;


            newHelperPin.value =
                "";

            confirmNewHelperPin.value =
                "";


            if (changePinMessage) {

                changePinMessage.textContent =
                    "";

                changePinMessage.classList.remove(
                    "error",
                    "success"
                );

                changePinMessage.style.display =
                    "none";

            }


            changePinOverlay.classList.add(
                "show"
            );


            setTimeout(
                function () {

                    newHelperPin.focus();

                },
                100
            );

        }


        function hideChangePinModal() {

            if (!changePinOverlay) {
                return;
            }


            changePinOverlay.classList.remove(
                "show"
            );

        }


        if (closeChangePin) {

            closeChangePin.addEventListener(
                "click",
                hideChangePinModal
            );

        }


        if (cancelChangePin) {

            cancelChangePin.addEventListener(
                "click",
                hideChangePinModal
            );

        }


        if (changePinOverlay) {

            changePinOverlay.addEventListener(
                "click",
                function (event) {

                    if (
                        event.target ===
                        changePinOverlay
                    ) {

                        hideChangePinModal();

                    }

                }
            );

        }


        /* =========================================
           SAVE NEW HELPER PIN
           ========================================= */

        if (changePinForm) {

            changePinForm.addEventListener(
                "submit",
                async function (event) {

                    event.preventDefault();


                    const helperId =
                        changePinHelperId.value;

                    const newPin =
                        newHelperPin
                            .value
                            .trim();

                    const confirmPin =
                        confirmNewHelperPin
                            .value
                            .trim();


                    if (
                        !/^\d{4}$/.test(
                            newPin
                        )
                    ) {

                        showChangePinMessage(
                            "PIN must contain exactly 4 numbers.",
                            "error"
                        );

                        return;
                    }


                    if (
                        newPin !==
                        confirmPin
                    ) {

                        showChangePinMessage(
                            "PINs do not match.",
                            "error"
                        );

                        return;
                    }


                    const submitButton =
                        changePinForm.querySelector(
                            ".helper-create-button"
                        );


                    if (submitButton) {

                        submitButton.disabled =
                            true;

                        submitButton.textContent =
                            "Changing...";

                    }


                    try {

                        const formData =
                            new FormData();


                        formData.append(
                            "helper_id",
                            helperId
                        );


                        formData.append(
                            "new_pin",
                            newPin
                        );


                        formData.append(
                            "confirm_pin",
                            confirmPin
                        );


                        const response =
                            await fetch(
                                "../api/change-helper-pin.php",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            );


                        const data =
                            await response.json();


                        if (!data.success) {

                            showChangePinMessage(
                                data.message,
                                "error"
                            );

                            return;
                        }


                        showChangePinMessage(
                            data.message,
                            "success"
                        );


                        setTimeout(
                            function () {

                                hideChangePinModal();


                                if (
                                    manageHelperOverlay
                                ) {

                                    manageHelperOverlay
                                        .classList
                                        .add(
                                            "show"
                                        );


                                    loadHelperProfiles();

                                }

                            },
                            700
                        );


                    } catch (error) {

                        console.error(
                            "Change PIN error:",
                            error
                        );


                        showChangePinMessage(
                            "Unable to change helper PIN.",
                            "error"
                        );

                    } finally {

                        if (submitButton) {

                            submitButton.disabled =
                                false;

                            submitButton.textContent =
                                "Change PIN";

                        }

                    }

                }
            );

        }


        function showChangePinMessage(
            message,
            type
        ) {

            if (!changePinMessage) {
                return;
            }


            changePinMessage.textContent =
                message;


            changePinMessage.classList.remove(
                "error",
                "success"
            );


            changePinMessage.classList.add(
                type
            );


            changePinMessage.style.display =
                "block";

        }


        /* =========================================
           ESCAPE HTML
           ========================================= */

        function escapeHtml(
            value
        ) {

            const element =
                document.createElement(
                    "div"
                );


            element.textContent =
                String(value);


            return element.innerHTML;

        }

    }
);