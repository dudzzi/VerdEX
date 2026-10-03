// =========================================
// VerdEX AI Assistant
// Visual interaction only
// =========================================

const aiMascot =
    document.getElementById(
        "verdexAiMascot"
    );

const aiChat =
    document.getElementById(
        "verdexAiChat"
    );

const aiClose =
    document.getElementById(
        "verdexAiClose"
    );

const aiForm =
    document.getElementById(
        "verdexAiForm"
    );

const aiInput =
    document.getElementById(
        "verdexAiInput"
    );

const aiMessages =
    document.getElementById(
        "verdexAiMessages"
    );

const suggestionButtons =
    document.querySelectorAll(
        ".verdex-ai-suggestions button"
    );


// =========================================
// OPEN CHAT
// =========================================

function openAiChat() {

    if (!aiChat) {
        return;
    }

    aiChat.classList.add(
        "open"
    );

    aiChat.setAttribute(
        "aria-hidden",
        "false"
    );

    if (aiInput) {
        setTimeout(
            function () {
                aiInput.focus();
            },
            200
        );
    }
}


// =========================================
// CLOSE CHAT
// =========================================

function closeAiChat() {

    if (!aiChat) {
        return;
    }

    aiChat.classList.remove(
        "open"
    );

    aiChat.setAttribute(
        "aria-hidden",
        "true"
    );
}


// =========================================
// MASCOT CLICK
// =========================================

if (aiMascot) {

    aiMascot.addEventListener(
        "click",
        function () {

            // Wave animation
            aiMascot.classList.remove(
                "waving"
            );

            void aiMascot.offsetWidth;

            aiMascot.classList.add(
                "waving"
            );


            setTimeout(
                function () {

                    aiMascot.classList.remove(
                        "waving"
                    );

                },
                2100
            );


            // Open chat
            openAiChat();
        }
    );
}


// =========================================
// CLOSE BUTTON
// =========================================

if (aiClose) {

    aiClose.addEventListener(
        "click",
        function () {

            closeAiChat();

        }
    );
}


// =========================================
// ADD USER MESSAGE
// =========================================

function addUserMessage(message) {

    if (!aiMessages) {
        return;
    }


    const messageElement =
        document.createElement(
            "div"
        );


    messageElement.className =
        "ai-message user";


    const bubble =
        document.createElement(
            "div"
        );


    bubble.className =
        "ai-message-bubble";


    bubble.textContent =
        message;


    messageElement.appendChild(
        bubble
    );


    aiMessages.appendChild(
        messageElement
    );


    aiMessages.scrollTop =
        aiMessages.scrollHeight;
}


// =========================================
// TEMPORARY ASSISTANT RESPONSE
// =========================================

function addTemporaryResponse() {

    if (!aiMessages) {
        return;
    }


    const messageElement =
        document.createElement(
            "div"
        );


    messageElement.className =
        "ai-message assistant";


    messageElement.innerHTML = `
        <div class="ai-message-avatar">
            🌱
        </div>

        <div class="ai-message-bubble">
            I'm not connected to the VerdEX AI yet.
            We'll add that next.
        </div>
    `;


    aiMessages.appendChild(
        messageElement
    );


    aiMessages.scrollTop =
        aiMessages.scrollHeight;
}


// =========================================
// FORM SUBMIT
// =========================================

if (aiForm) {

    aiForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            if (!aiInput) {
                return;
            }


            const message =
                aiInput.value.trim();


            if (message === "") {
                return;
            }


            addUserMessage(
                message
            );


            aiInput.value = "";


            setTimeout(
                addTemporaryResponse,
                400
            );
        }
    );
}


// =========================================
// SUGGESTED QUESTIONS
// =========================================

suggestionButtons.forEach(
    function (button) {

        button.addEventListener(
            "click",
            function () {

                const question =
                    button.textContent.trim();


                if (aiInput) {
                    aiInput.value =
                        question;
                }


                if (aiForm) {
                    aiForm.requestSubmit();
                }
            }
        );
    }
);


// =========================================
// ESC KEY
// =========================================

document.addEventListener(
    "keydown",
    function (event) {

        if (
            event.key ===
            "Escape"
        ) {

            closeAiChat();
        }
    }
);