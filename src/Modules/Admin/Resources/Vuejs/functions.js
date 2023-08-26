import html2canvas from 'html2canvas'
export function myTrans(text){
    return text + ".";
}
export function resizeBase64Img(base64, newWidth, newHeight){
    return new Promise((resolve, reject) => {
        // Tạo một đối tượng hình ảnh mới
        const img = new Image();

        // Xử lý sự kiện khi hình ảnh được tải thành công
        img.onload = () => {
            // Tạo một canvas để vẽ lại ảnh với kích thước mới
            const canvas = document.createElement('canvas');
            canvas.width = newWidth;
            canvas.height = newHeight;

            // Vẽ lại ảnh lên canvas với kích thước mới
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, newWidth, newHeight);

            // Lấy base64 của ảnh đã được resize từ canvas
            const resizedBase64 = canvas.toDataURL("image/png;base64");

            // Giải phóng bộ nhớ và giải quyết Promise với base64 đã được resize
            resolve(resizedBase64);
        };

        // Xử lý sự kiện khi hình ảnh gặp lỗi
        img.onerror = error => {
            reject(error);
        };

        // Thiết lập định dạng base64 cho hình ảnh và chờ nó tải
        img.src = base64;
    })
}

export function takephoto(){
    console.log("call takephoto")
    return new Promise((resolve) => {
        html2canvas(document.body, {
            logging: false,
            useCORS: true,
            imageTimeout: 5000,
            onclone: (cloneDoc) => {
                const style = cloneDoc.createElement('style');
                style.innerHTML = '* { animation-name: unset !important; -webkit-animation-duration: 0s !important; ' +
                    'animation-duration: 0s !important; -webkit-animation-fill-mode: none !important; animation-fill-mode: none !important; ' +
                    '-webkit-transition: none !important; -moz-transition: none !important; -o-transition: none !important; transition: none !important; }';
                cloneDoc.body.appendChild(style);
                console.log("cloneDoc.body.appendChild(style)");
            }
        })
            .then((canvas) => {
                console.log("canvas.toDataURL");
                let imageUrl = canvas.toDataURL("image/png;base64");
                let w = window.screen.width,
                    h = window.screen.height
                resizeBase64Img(imageUrl, w, h)
                    .then(resizedBase64 => {
                        resolve(resizedBase64)
                    })
                    .catch(error => {
                        console.error(error);
                    });
            })

    })

}
