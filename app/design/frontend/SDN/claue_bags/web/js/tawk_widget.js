// Embed Tawk.to chat widget
var s1 = document.createElement("script");
s1.async = true;
s1.src = "https://embed.tawk.to/64be447ccc26a871b02ad723/1h63jn28c";
s1.charset = "UTF-8";
s1.setAttribute("crossorigin", "*");
document.getElementsByTagName("script")[0].parentNode.insertBefore(s1, document.getElementsByTagName("script")[0]);

// Remove Tawk.to branding
function removeBranding() {
    // try {
    //     var element = document.querySelector("iframe[title*=chat]:nth-child(2)").contentDocument.querySelector("a[class*=tawk-branding]");
    //     if (element) {
    //         element.remove();
    //     }
    // } catch (e) {
    //     if (e instanceof TypeError) {
    //         console.log('app/design/frontend/SDN/claue_bags/web/js/tawk_widget.js: элемент уже удален');
    //     } else {
    //         console.error('app/design/frontend/SDN/claue_bags/web/js/tawk_widget.js: произошла ошибка', e);
    //     }
    // }

    try {
        var iframe = document.querySelector("iframe[title*=chat]:nth-child(2)");
        if (iframe) {
            var element = iframe.contentDocument.querySelector("a[class*=tawk-branding]");
            if (element) {
                element.remove();
            }
        }
    } catch (e) {
        if (e instanceof TypeError) {
            console.log('app/design/frontend/SDN/claue_bags/web/js/tawk_widget.js: элемент уже удален');
        } else {
            console.error('app/design/frontend/SDN/claue_bags/web/js/tawk_widget.js: произошла ошибка', e);
        }
    }
}

var tick = 300;

setInterval(removeBranding, tick);