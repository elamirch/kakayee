<x-admin>
    <form action="/cards" method="POST">
        @csrf
        <div style="display: flex; gap: 20px;">
            <div style="width: 50%">
            
            <h1>اطلاعات کلی سوال</h1><br>
            {{-- Card Title --}}
            <input type="text" name="card_title" placeholder="نام سوال"><br>

            {{-- Card Content --}}
            <textarea class="w-full max-w-xl sm:max-w-md md:max-w-lg"
            oninput="updatePreview()" name="card_content"
            rows="6" cols="50" placeholder="محتوای سوال"
            id="card_content"></textarea><br>
            
            {{--Card Date--}}
            <input type="text" name="card_date" placeholder="تاریخ سوال (مثلا شهریور ۱۴۰۳)">
            <br><br><hr><br>
            <h1>افزودن فرم پاسخ</h1><br>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                onclick="addTextInput()">تکست‌باکس</button><br><br><br>
                
            <input type="text" id="option1" placeholder="گزینه ۱">
            <input type="text" id="option2" placeholder="گزینه ۲">
            <input type="text" id="option3" placeholder="گزینه ۳">
            <input type="text" id="option4" placeholder="گزینه ۴">
            <br>
            <br>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                onclick="addRadioButtons()">۴ گزینه</button><br><br><hr><br>
            </div>
            <div style="width: 50%; padding: 20px; border-color: black; border-width: 2px; border-radius: 5px;"
                class="preview" id="live_preview">Live preview...</div>
        </div>

        <h1>پاسخ سوال</h1><br>
        <input type="text" name="card_answer" placeholder="Card Answer"><br><br><hr><br>

        {{--Card Explanation--}}
        <h1>پاسخ تشریحی</h1><br>
        <textarea class="w-full max-w-xl sm:max-w-md md:max-w-lg"
        name="card_explanation" rows="6" cols="50" oninput="updatePreview()"
            placeholder="توضیحات این فرم، پس از پاسخ به سوال نمایش داده می‌شوند" id="card_explanation"></textarea>
            <br><br><hr><br>
        
        <x-primary-button>اتمام</x-primary-button>
    </form>
    <script>

        function updatePreview() {
            document.getElementById("live_preview").innerHTML = document.getElementById("card_content").value;
        }

        function addTextInput() {
            let textArea = document.getElementById("card_content");
            textArea.value += '\n\n<br><br><input type="text" name="answer" placeholder="پاسخ">';
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