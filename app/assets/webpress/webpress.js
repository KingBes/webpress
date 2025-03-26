// 主题切换函数
const changeTheme = () => {
    document.querySelector('html').classList.toggle('dark')
}
const changeBtn = (func, $eve) => {
    const x = $eve.clientX
    const y = $eve.clientY
    // 计算鼠标点击位置距离视窗的最大圆半径
    const endRadius = Math.hypot(
        Math.max(x, innerWidth - x),
        Math.max(y, innerHeight - y),
    )
    document.documentElement.style.setProperty('--x', x + 'px')
    document.documentElement.style.setProperty('--y', y + 'px')
    document.documentElement.style.setProperty('--r', endRadius + 'px')
    // 判断浏览器是否支持document.startViewTransition
    if (document.startViewTransition) {
        // 如果支持就使用document.startViewTransition方法
        document.startViewTransition(() => {
            func.call() // 这里的函数是切换主题的函数，调用changeBtn函数时进行传入
        })
    } else {
        // 如果不支持，就使用最原始的方式，切换主题
        func.call()
    }
}
htmx.on("#is_dark", "click", function (e) {
    let find = this.querySelector("i")
    if (find.getAttribute("class") == "icon icon-yueliang") {
        find.setAttribute("class", "icon icon-taiyang")
        changeBtn(changeTheme, e)
    } else {
        find.setAttribute("class", "icon icon-yueliang")
        changeBtn(changeTheme, e)
    }
})