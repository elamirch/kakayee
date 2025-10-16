<x-admin>
    <form action="/cards" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Left Column – Editor --}}
            <div class="space-y-4">
                <h1 class="text-2xl font-bold mb-2">📝 اطلاعات کلی سوال</h1>

                {{-- Card Title --}}
                <input type="text" name="card_title" placeholder="نام سوال" 
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500">

                {{-- Toolbar --}}
                <div class="flex flex-wrap items-center gap-2 bg-gray-100 p-2 rounded-md text-sm">
                    <button type="button" onclick="applyFormat('card_content','bold')" class="px-2 py-1 hover:bg-gray-200 rounded">Bold</button>
                    <button type="button" onclick="applyFormat('card_content','italic')" class="px-2 py-1 hover:bg-gray-200 rounded">Italic</button>
                    <button type="button" onclick="applyFormat('card_content','h1')" class="px-2 py-1 hover:bg-gray-200 rounded">H1</button>
                    <button type="button" onclick="applyFormat('card_content','ul')" class="px-2 py-1 hover:bg-gray-200 rounded">List</button>
                    <button type="button" onclick="addTextInput('card_content')" class="px-2 py-1 bg-gray-900   rounded hover:bg-gray-800">➕ تکست‌باکس</button>
                    <button type="button" onclick="addRadioButtons('card_content')" class="px-2 py-1 bg-gray-900   rounded hover:bg-gray-800">➕ ۴ گزینه‌ای</button>
                    <label for="image_upload_main" class="cursor-pointer px-2 py-1 bg-indigo-700   rounded hover:bg-indigo-600">
                        📷 افزودن تصویر
                    </label>
                    <input type="file" id="image_upload_main" class="hidden" accept="image/*" onchange="uploadImage(this, 'card_content')">
                </div>

                {{-- Card Content Editor --}}
                <textarea name="card_content" id="card_content" rows="10"
                    class="w-full border rounded-lg p-3 font-mono focus:ring-2 focus:ring-indigo-500"
                    placeholder="از Markdown یا HTML استفاده کنید..." oninput="updatePreview('card_content')"></textarea>

                {{-- Card Date --}}
                <input type="text" name="card_date" placeholder="تاریخ سوال (مثلاً شهریور ۱۴۰۳)"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Right Column – Live Preview --}}
            <div>
                <h1 class="text-2xl font-bold mb-2">🔍 پیش‌نمایش زنده</h1>
                <div id="live_preview_card_content" class="border rounded-lg p-4 bg-white shadow-sm min-h-[400px] overflow-auto">
                    Live preview...
                </div>
            </div>
        </div>

        {{-- Card Answer --}}
        <div>
            <h1 class="text-xl font-bold mt-6 mb-2">پاسخ سوال</h1>
            <input type="text" name="card_answer" placeholder="پاسخ صحیح"
                class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- Explanation Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h1 class="text-xl font-bold mt-6 mb-2">📘 پاسخ تشریحی</h1>

                {{-- Toolbar for Explanation --}}
                <div class="flex flex-wrap items-center gap-2 bg-gray-100 p-2 rounded-md text-sm">
                    <button type="button" onclick="applyFormat('card_explanation','bold')" class="px-2 py-1 hover:bg-gray-200 rounded">Bold</button>
                    <button type="button" onclick="applyFormat('card_explanation','italic')" class="px-2 py-1 hover:bg-gray-200 rounded">Italic</button>
                    <button type="button" onclick="applyFormat('card_explanation','h1')" class="px-2 py-1 hover:bg-gray-200 rounded">H1</button>
                    <button type="button" onclick="applyFormat('card_explanation','ul')" class="px-2 py-1 hover:bg-gray-200 rounded">List</button>
                    <label for="image_upload_explanation" class="cursor-pointer px-2 py-1 bg-indigo-700   rounded hover:bg-indigo-600">
                        📷 افزودن تصویر
                    </label>
                    <input type="file" id="image_upload_explanation" class="hidden" accept="image/*" onchange="uploadImage(this, 'card_explanation')">
                </div>

                {{-- Explanation Editor --}}
                <textarea name="card_explanation" id="card_explanation" rows="8"
                    class="w-full border rounded-lg p-3 font-mono focus:ring-2 focus:ring-indigo-500"
                    placeholder="توضیحات پس از پاسخ به سوال..." oninput="updatePreview('card_explanation')"></textarea>
            </div>

            {{-- Explanation Preview --}}
            <div>
                <h1 class="text-2xl font-bold mb-2">🔍 پیش‌نمایش پاسخ تشریحی</h1>
                <div id="live_preview_card_explanation" class="border rounded-lg p-4 bg-white shadow-sm min-h-[300px] overflow-auto">
                    Live preview...
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="pt-6">
            <x-primary-button>اتمام</x-primary-button>
        </div>
    </form>

    {{-- Dependencies --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <script>
        // --- Shared Markdown Editor Logic ---
        function updatePreview(id) {
            const content = document.getElementById(id).value;
            document.getElementById(`live_preview_${id}`).innerHTML = marked.parse(content);
        }

        function setSelectionRange(textarea, start, end) {
            textarea.focus();
            textarea.setSelectionRange(start, end);
        }

        function toggleWrap(textarea, wrapperStart, wrapperEnd = null) {
            if (wrapperEnd === null) wrapperEnd = wrapperStart;
            const val = textarea.value;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;

            if (start === end) {
                const insert = wrapperStart + wrapperEnd;
                textarea.value = val.slice(0, start) + insert + val.slice(end);
                const caretPos = start + wrapperStart.length;
                setSelectionRange(textarea, caretPos, caretPos);
                return;
            }

            const selected = val.slice(start, end);
            const before = val.slice(Math.max(0, start - wrapperStart.length), start);
            const after = val.slice(end, Math.min(val.length, end + wrapperEnd.length));
            const isWrapped = (before === wrapperStart && after === wrapperEnd);

            if (isWrapped) {
                textarea.value = val.slice(0, start - wrapperStart.length)
                    + selected
                    + val.slice(end + wrapperEnd.length);
                setSelectionRange(textarea, start - wrapperStart.length, start - wrapperStart.length + selected.length);
            } else {
                textarea.value = val.slice(0, start)
                    + wrapperStart + selected + wrapperEnd
                    + val.slice(end);
                setSelectionRange(textarea, start, end + wrapperStart.length + wrapperEnd.length);
            }
        }

        function toggleLinePrefix(textarea, prefix) {
            const val = textarea.value;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const before = val.slice(0, start);
            const selected = val.slice(start, end);
            const after = val.slice(end);

            const lines = selected.split('\n');
            const allHave = lines.length > 0 && lines.every(line => line.startsWith(prefix));

            const newSelected = allHave
                ? lines.map(line => line.startsWith(prefix) ? line.slice(prefix.length) : line).join('\n')
                : lines.map(line => (line.trim().length === 0) ? line : prefix + line).join('\n');

            textarea.value = before + newSelected + after;
            setSelectionRange(textarea, start, start + newSelected.length);
        }

        function applyFormat(id, format) {
            const ta = document.getElementById(id);
            switch (format) {
                case 'bold': toggleWrap(ta, '**', '**'); break;
                case 'italic': toggleWrap(ta, '_', '_'); break;
                case 'h1': toggleLinePrefix(ta, '# '); break;
                case 'ul': toggleLinePrefix(ta, '- '); break;
            }
            updatePreview(id);
        }

        function insertAtCursor(textarea, text) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const val = textarea.value;
            textarea.value = val.slice(0, start) + text + val.slice(end);
            const pos = start + text.length;
            setSelectionRange(textarea, pos, pos);
        }

        function addTextInput(id) {
            const markup = '\n\n<input type="text" name="answer" placeholder="پاسخ" />\n\n';
            const ta = document.getElementById(id);
            insertAtCursor(ta, markup);
            updatePreview(id);
        }

        function addRadioButtons(id) {
            const options = ['گزینه ۱', 'گزینه ۲', 'گزینه ۳', 'گزینه ۴'].map((o) =>
                `<label><input type="radio" name="answer" value="${o}"> ${o}</label>`).join('\n');
            const markup = `\n\n<div class="radio-group">\n${options}\n</div>\n\n`;
            const ta = document.getElementById(id);
            insertAtCursor(ta, markup);
            updatePreview(id);
        }

        async function uploadImage(input, targetId) {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append("image", file);

            const response = await fetch("/upload-image", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            const data = await response.json();
            if (data.url) {
                const ta = document.getElementById(targetId);
                insertAtCursor(ta, `\n\n![تصویر](${data.url})\n\n`);
                updatePreview(targetId);
            } else {
                alert("خطا در آپلود تصویر");
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updatePreview('card_content');
            updatePreview('card_explanation');
        });
    </script>
</x-admin>
