var text_viewer_position = document.getElementsByClassName("text-viewer-position"),
    text_viewer = document.getElementById("text-viewer"),
    centertopbottom = document.getElementById("centertopbottom"),
    centerleftright = document.getElementById("centerleftright"),
    center_text_viewer = document.getElementsByClassName("center-text-viewer");

var textcolorinput = document.getElementById("textcolorinput");

if (textcolorinput != null) {
    textcolorinput.onchange = wpuc_changeTextColor;
}


function wpuc_changeTextColor() {
    text_viewer.style.color = textcolorinput.value;
}

var textsizeinput = document.getElementById("textsizeinput");

if (textsizeinput != null) {
    textsizeinput.onkeyup = wpuc_changetextsizeinput;
    textsizeinput.onchange = wpuc_changetextsizeinput;
}

function wpuc_changetextsizeinput() {
    text_viewer.style.fontSize = textsizeinput.value + "px";
}

for (let i = 0; i < text_viewer_position.length; i = i + 1) {
    text_viewer_position[i].onchange = wpuc_textviewerfunction;
    text_viewer_position[i].onkeyup = wpuc_textviewerfunction;

    function wpuc_textviewerfunction() {
        if (this.getAttribute("data-position") == "top" && centertopbottom.checked == false) {
            text_viewer.style.top = this.value + "px";
        } else if (this.getAttribute("data-position") == "left" && centerleftright.checked == false) {
            text_viewer.style.left = this.value + "px";
        }
    }
}


for (let i = 0; i < center_text_viewer.length; i = i + 1) {
    center_text_viewer[i].onclick = function () {
        if (centertopbottom.checked == true && centerleftright.checked == true) {
            text_viewer.classList.add(this.getAttribute("data-class-same"));
            text_viewer.classList.remove(centertopbottom.getAttribute("data-class"));
            text_viewer.classList.remove(centerleftright.getAttribute("data-class"));
        } else if (this.checked == true) {
            text_viewer.classList.add(this.getAttribute("data-class"));
        } 
        
        if (centertopbottom.checked == false && centerleftright.checked == true) {
            text_viewer.classList.remove(this.getAttribute("data-class-same"));
            text_viewer.classList.add(centerleftright.getAttribute("data-class"));
        } else if (centertopbottom.checked == true && centerleftright.checked == false) {
            text_viewer.classList.remove(this.getAttribute("data-class-same"));
            text_viewer.classList.add(centertopbottom.getAttribute("data-class"));
        } else {
            text_viewer.classList.remove(this.getAttribute("data-class"));
        }

        text_viewer.style.top = text_viewer_position[0].value + "px";
        text_viewer.style.left = text_viewer_position[1].value + "px";
    }
}