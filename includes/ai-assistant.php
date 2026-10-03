<!-- =========================================
     VerdEX AI Assistant
     ========================================= -->

<div
    class="verdex-ai-assistant"
    id="verdexAiAssistant"
>

    <!-- Mascot -->
    <button
        class="verdex-ai-mascot"
        id="verdexAiMascot"
        type="button"
        aria-label="Open VerdEX AI Assistant"
    >

        <div class="mascot-character">

            <div class="mascot-leaf">
                🌿
            </div>

            <div class="mascot-head">

                <span class="mascot-eye left"></span>

                <span class="mascot-eye right"></span>

                <span class="mascot-mouth"></span>

            </div>

            <div class="mascot-body">

                <span class="mascot-arm mascot-arm-left"></span>

                <span class="mascot-arm mascot-arm-right"></span>

            </div>

        </div>

    </button>


    <!-- Chat Window -->
    <div
        class="verdex-ai-chat"
        id="verdexAiChat"
        aria-hidden="true"
    >

        <!-- Header -->
        <div class="verdex-ai-header">

            <div class="verdex-ai-title">

                <div class="verdex-ai-avatar">
                    🌱
                </div>

                <div>
                    <strong>
                        VerdEX Assistant
                    </strong>

                    <span>
                        Your farm companion
                    </span>
                </div>

            </div>


            <button
                type="button"
                class="verdex-ai-close"
                id="verdexAiClose"
                aria-label="Close assistant"
            >
                ×
            </button>

        </div>


        <!-- Messages -->
        <div
            class="verdex-ai-messages"
            id="verdexAiMessages"
        >

            <div class="ai-message assistant">

                <div class="ai-message-avatar">
                    🌱
                </div>

                <div class="ai-message-bubble">

                    Hi! I'm your VerdEX farm companion.

                    <br><br>

                    Ask me anything about your farm.

                </div>

            </div>

        </div>


        <!-- Suggested Questions -->
        <div class="verdex-ai-suggestions">

            <button type="button">
                How is my farm?
            </button>

            <button type="button">
                Check my sensors
            </button>

            <button type="button">
                What should I do today?
            </button>

        </div>


        <!-- Input -->
        <form
            class="verdex-ai-input"
            id="verdexAiForm"
        >

            <input
                type="text"
                id="verdexAiInput"
                placeholder="Ask about your farm..."
                autocomplete="off"
            >

            <button
                type="submit"
                aria-label="Send message"
            >
                ➤
            </button>

        </form>

    </div>

</div>