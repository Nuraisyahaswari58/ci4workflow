const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
    if (currentTheme === 'dark') {
        localStorage.setItem('theme', 'dark')
        $("body").addClass("dark-skin")
        $("body").removeClass("light-skin")
        $(".icon-Moon").hide()
        $(".icon-Sun").show()
    } else {
        localStorage.setItem('theme', 'light')
        $("body").removeClass("dark-skin")
        $("body").addClass("light-skin")
        $(".icon-Moon").show()
        $(".icon-Sun").hide()
    }
} else {
    localStorage.setItem('theme', 'light');
}

function switchTheme() {
    if (localStorage.getItem('theme') == 'light') {
        localStorage.setItem('theme', 'dark')
        $("body").addClass("dark-skin")
        $("body").removeClass("light-skin")
        $(".icon-Moon").hide()
        $(".icon-Sun").show()
    } else {
        localStorage.setItem('theme', 'light')
        $("body").removeClass("dark-skin")
        $("body").addClass("light-skin")
        $(".icon-Moon").show()
        $(".icon-Sun").hide()
    }
}