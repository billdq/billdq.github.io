function validate(sel, name) {
    var selVal = $(sel).val();
    if (selVal == null || selVal == "") {
        alert("Please input " + name);
        return false;
    }
    return true;
}

