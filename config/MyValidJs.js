function ValidUrl(str, id) {
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    if (str == '') {
        document.getElementById(id + "_error").innerHTML = 'This Field is Required';
        return false;
    }
    if (!pattern.test(str)) {
        document.getElementById(id + "_error").innerHTML = 'Enter Correct Url';
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    }
}

function SelectCheck(data, id) {
    if (data == '0') {
        document.getElementById(id + "_error").innerHTML = ' Select';
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    }
}

function ConfirmNewpassword(pass, id) {
    var newpass = pass;
    var oldpass = document.getElementById("newpassword").value;
    if (newpass == '') {
        document.getElementById(id + "_error").innerHTML = 'This field is required.';
        return false;
    }
    if (newpass != oldpass) {
        document.getElementById(id + "_error").innerHTML = "Confirm Password And New Password Not Matched";
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    }
}

function ContactNoCheck(ContactNo, id) {
    var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    if (ContactNo == '') {
        document.getElementById(id + "_error").innerHTML = 'This field is required.';
        return false;
    } else if (!ContactNo.match(phoneno)) {
        document.getElementById(id + "_error").innerHTML = "Enter the 10 Digit Mobile Number";
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    }
}

function BlankCheck(txt, id) {
    if (txt == '') {
        document.getElementById(id + "_error").innerHTML = 'This field is required.';
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    }
}

function securepassword(txt, id) {
    if (txt == '') {
        document.getElementById(id + "_error").innerHTML = 'This field is required.';
        return false;
    }
    if (txt.length < 6) {
        document.getElementById(id + "_error").innerHTML = 'Password should be 6 character long.';
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;

    }
}

function EmailCheck(txt, id) {
    var id = id;
    if (txt == '') {
        document.getElementById(id + "_error").innerHTML = 'This field is required.';
        return false;
    }
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (regex.test(txt)) {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    } else {
        document.getElementById(id + "_error").innerHTML = 'Please enter a valid email address.';
        return false;
    }
}

function zipcode(ContactNo, id) {

    if (ContactNo == '') {
        document.getElementById(id + "_error").innerHTML = 'This field is required.';
        return false;
    } else if (isNaN(ContactNo) && (ContactNo.length < 6)) {
        document.getElementById(id + "_error").innerHTML = "Enter the Correct Zipcode ";
        return false;
    } else {
        document.getElementById(id + "_error").innerHTML = '';
        return true;
    }
}
/*=========== ============== ================== ================= ============================= ========================== ================================== ===*/

function BlogCreat() {
    var a = BlankCheck(document.getElementById("Title").value, "Title");
    var b = BlankCheck(document.getElementById("Category").value, "Category");
    var c = BlankCheck(document.getElementById("Date").value, "Date");

    if (a && b && c) {
        return true;
    } else {
        return false;
    }
}
/*=========== ============== ================== ================= ============================= ========================== ================================== ===*/

function BlogCatCreat() {
    var a = BlankCheck(document.getElementById("Name").value, "Name");

    if (a) {
        return true;
    } else {
        return false;
    }
}















