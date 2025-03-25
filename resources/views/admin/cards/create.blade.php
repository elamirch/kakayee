<x-admin>
    <form action="/cards" method="POST">
        @csrf
        <div style="display: flex; gap: 20px;">
            <div style="width: 50%">
            <input type="text" name="card_title" placeholder="Card Title"><br>

            <textarea oninput="updatePreview()" name="card_content" rows="6" cols="50" placeholder="Card Content" id="card_content"></textarea><br>
            <br><hr><br>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                onclick="addTextInput()">Add Text Input</button><br><br><hr><br>
                
            <input type="text" id="option1" placeholder="Option 1">
            <input type="text" id="option2" placeholder="Option 2">
            <input type="text" id="option3" placeholder="Option 3">
            <input type="text" id="option4" placeholder="Option 4"><br>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                onclick="addRadioButtons()">Add Radio Buttons</button><br><br><hr><br>
            </div>
            <div style="width: 50%; padding: 20px; border-color: black; border-width: 2px; border-radius: 5px;"
                class="preview" id="live_preview">Live preview...</div>
        </div>
        
        <input type="text" name="card_answer" placeholder="Card Answer"><br><br><hr><br>
        <x-primary-button>Add Card</x-primary-button>
    </form>
    <script>

        function updatePreview() {
            document.getElementById("live_preview").innerHTML = document.getElementById("card_content").value;
        }

        function addTextInput() {
            let textArea = document.getElementById("card_content");
            textArea.value += '\n\n<br><br><input type="text" name="answer" placeholder="Answer">';
            updatePreview();
        }

        function addRadioButtons() {
            let textArea = document.getElementById("card_content");
            let options = [];

            for (let i = 1; i <= 4; i++) {
                let value = document.getElementById("option" + i).value || "Option " + i;
                options.push(`<input type="radio" id="choice${i}" name="answer" value="${value}"> <label for="choice${i}">${value}</label><br>`);
            }

            textArea.value += '\n\n<br><br>' + options.join("\n");
            updatePreview();
        }
    </script>
</x-admin>