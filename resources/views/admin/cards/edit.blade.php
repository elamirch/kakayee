<x-admin>
    <form action="/cards/{{$card->id}}" method="POST">
        @csrf
        @method('PUT')
        <div style="display: flex; gap: 20px;">
            <div style="width: 50%">

                <h1>اطلاعات کلی سوال</h1><br>
                {{--Card Title--}}
                <span>نام سوال:</span>
                <input type="text" name="card_title" placeholder="نام سوال"
                    value="{{ $card->title}}">
                <br>

                {{--Card Content--}}
                <br><span>محتوای سوال:</span><br>
                <textarea oninput="updatePreview()" name="card_content" cols="50"
                    rows="6" class="w-full max-w-xl sm:max-w-md md:max-w-lg"
                    placeholder="محتوای سوال" id="card_content">{{ $card->content}}
                </textarea>

                {{--Card Date--}}
                <br><br><span>تاریخ سوال:</span>
                <input type="text" name="card_date" placeholder="تاریخ سوال (مثلا شهریور ۱۴۰۳)"
                value="{{ $card->date }}">
                <br><br><hr><br>
                
                <h1>افزودن فرم پاسخ</h1><br>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                onclick="addTextInput()">افزودن تکست‌باکس</button><br><br><br>
                
                <input type="text" id="option1" placeholder="گزینه ۱">
                <input type="text" id="option2" placeholder="گزینه ۲">
                <input type="text" id="option3" placeholder="گزینه ۳">
                <input type="text" id="option4" placeholder="گزینه ۴">
                <br>
                <br>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    onclick="addRadioButtons()">افزودن ۴ گزینه</button><br><br><hr><br>
                </div>

                {{-- Live preview --}}
                <div style="width: 50%; padding: 20px; border-color: black; border-width: 2px; border-radius: 5px;"
                    class="preview" id="live_preview">Live preview...</div>
        </div>
        
        <span>پاسخ:</span>
        <input type="text" name="card_answer" placeholder="Card Answer"
            value="{{ $card->answer}}">
        <br><br><hr><br>
        {{--Card Explanation--}}
        <h1>پاسخ تشریحی</h1><br>
        <textarea class="w-full max-w-xl sm:max-w-md md:max-w-lg"
        name="card_explanation" rows="6" cols="50" oninput="updatePreview()"
            placeholder="توضیحات این فرم، پس از پاسخ به سوال نمایش داده می‌شوند" id="card_explanation">{{ $card->explanation }}</textarea>
            <br><br><hr><br>
        <x-primary-button>ذخیره</x-primary-button>
    </form>
    <br><hr><br>
    <form action="/cards/{{$card->id}}" method="post">
        @csrf
        @method('DELETE')
        <span>حذف کارت: </span>
        <x-primary-button class="bg-red">حذف</x-primary-button>
    </form>

    <script>
        document.onload(updatePreview());
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