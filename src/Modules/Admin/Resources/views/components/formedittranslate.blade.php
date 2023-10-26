<div>
    @if(isAdmin())
    <div id="myModal" class="translatemodal">
        <div class="modal-content">
            <span class="close" onclick="showTranslateModal()">&times;</span>
            <div class="container">
                <form name="translate_form" id="translate_form" method="post">
                    <p for="translate_text">Từ khóa: <b id="translate_text_default"></b></p>
                    <p style="padding-top: 10px; padding-bottom: 10px" for="translate_text">Sửa nội dung dịch theo ngôn ngữ: <b id="translate_lang_name"></b></p>
                    <textarea id="translate_text" name="translate_text">Some text...</textarea>
                    <input type="hidden" id="translate_key" name="translate_key" value="">
                    <input type="submit" value="Lưu nôi dung dịch">
                    <div style="padding: 10px; margin-top: 10px; background: #f3e0b4">
                        Lưu ý khi dịch nội các từ khóa trong ngoặc nhọn thì vẫn giữ nguyên<br>
                        Ví dụ: {currentlang}
                    </div>
                </form>
            </div>
        </div>
    </div>
        <style>
            .translatemodal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }

            .translatemodal .modal-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 1rem;
                border-radius: 0.25rem;
                width: 90%;
                max-width: 500px;
            }

            .translatemodal .modal-content .close {
                float: right;
                cursor: pointer;
                font-size: 1.5rem;
            }

            .translatemodal .modal-content .container {
                clear: both;
            }

            .translatemodal .modal-content .container p {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }

            .translatemodal .modal-content .container textarea {
                width: 100%;
                border: 1px solid #d1d5db;
                border-radius: 0.25rem;
                padding: 0.5rem;
                margin-top: 0.5rem;
                resize: vertical;
            }

            .translatemodal .modal-content .container input[type="submit"] {
                display: block;
                margin-top: 0.5rem;
                padding: 0.5rem 1rem;
                background: #3b82f6;
                color: white;
                border: none;
                border-radius: 0.25rem;
                cursor: pointer;
                transition: background 0.3s;
            }

            .translatemodal .modal-content input[type="submit"]:hover {
                background: #2563eb;
            }

            .translatemodal .modal-content .container div {
                padding: 0.5rem;
                margin-top: 0.5rem;
                background: #f3e0b4;
                border-radius: 0.25rem;
            }

        </style>
        <script type="application/javascript">
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#translate_form").submit(function (event) {
                    $.post("{{myroute('translate.update')}}", $("#translate_form").serialize())
                        .done(function( data ) {
                            document.getElementById("myModal").style.display = "none";
                            alert(data);
                            window.location.reload();
                        });
                    event.preventDefault();
                });
            });
            function showTranslateModal(id_edit){
                $.get("{{myroute('translate.edit')}}?key="+id_edit, function(data, status){
                    document.getElementById("translate_text_default").innerText = data.default || null;
                    document.getElementById("translate_text").value = data.text || null;
                    document.getElementById("translate_key").value = id_edit || null;
                    document.getElementById("translate_lang_name").innerText = data.lang_name || null;
                });
                const modal = document.getElementById('myModal');
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                } else {
                    modal.style.display = 'block';
                    window.onclick = function(event) {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    }
                    const closeButton = modal.querySelector('.close');
                    closeButton.onclick = function() {
                        modal.style.display = 'none';
                    }
                }
            }
        </script>
    @endif
</div>


