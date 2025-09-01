"use strict";

let mainContent;
(function () {
    let html = document.querySelector('html');
    mainContent = document.querySelector('.main-content');

    if (document.querySelector("#switcher-canvas")) {
        localStorageBackup2();
        switcherClick();
        checkOptions();
        setTimeout(() => {
            checkOptions();
        }, 1000);
    }

})();

function switcherClick() {
    let ltrBtn, rtlBtn, verticalBtn, horiBtn, lightBtn, darkBtn, boxedBtn, fullwidthBtn, scrollHeaderBtn, scrollMenuBtn, fixedHeaderBtn, fixedMenuBtn, lightHeaderBtn, darkHeaderBtn, colorHeaderBtn, gradientHeaderBtn, lightMenuBtn, darkMenuBtn, colorMenuBtn, gradientMenuBtn, transparentMenuBtn, transparentHeaderBtn, regular, classic, modern, defaultBtn, closedBtn, iconTextBtn, detachedBtn, overlayBtn, doubleBtn, menuClickBtn, menuHoverBtn, iconClickBtn, iconHoverBtn, primaryDefaultColor1Btn, primaryDefaultColor2Btn, primaryDefaultColor3Btn, primaryDefaultColor4Btn, primaryDefaultColor5Btn, bgDefaultColor1Btn, bgDefaultColor2Btn, bgDefaultColor3Btn, bgDefaultColor4Btn, bgDefaultColor5Btn, bgImage1Btn, bgImage2Btn, bgImage3Btn, bgImage4Btn, bgImage5Btn, ResetAll, resetBtn, loaderEnable, loaderDisable;
    let html = document.querySelector('html');
    lightBtn = document.querySelector('#switcher-light-theme');
    darkBtn = document.querySelector('#switcher-dark-theme');
    ltrBtn = document.querySelector('#switcher-ltr');
    rtlBtn = document.querySelector('#switcher-rtl');
    verticalBtn = document.querySelector('#switcher-vertical');
    horiBtn = document.querySelector('#switcher-horizontal');
    boxedBtn = document.querySelector('#switcher-boxed');
    fullwidthBtn = document.querySelector('#switcher-full-width');
    fixedMenuBtn = document.querySelector('#switcher-menu-fixed');
    scrollMenuBtn = document.querySelector('#switcher-menu-scroll');
    fixedHeaderBtn = document.querySelector('#switcher-header-fixed');
    scrollHeaderBtn = document.querySelector('#switcher-header-scroll');
    lightHeaderBtn = document.querySelector('#switcher-header-light');
    darkHeaderBtn = document.querySelector('#switcher-header-dark');
    colorHeaderBtn = document.querySelector('#switcher-header-primary');
    gradientHeaderBtn = document.querySelector('#switcher-header-gradient');
    transparentHeaderBtn = document.querySelector('#switcher-header-transparent');
    lightMenuBtn = document.querySelector('#switcher-menu-light');
    darkMenuBtn = document.querySelector('#switcher-menu-dark');
    colorMenuBtn = document.querySelector('#switcher-menu-primary');
    gradientMenuBtn = document.querySelector('#switcher-menu-gradient');
    transparentMenuBtn = document.querySelector('#switcher-menu-transparent');
    regular = document.querySelector('#switcher-regular');
    classic = document.querySelector('#switcher-classic');
    modern = document.querySelector('#switcher-modern');
    defaultBtn = document.querySelector('#switcher-default-menu');
    menuClickBtn = document.querySelector('#switcher-menu-click');
    menuHoverBtn = document.querySelector('#switcher-menu-hover');
    iconClickBtn = document.querySelector('#switcher-icon-click');
    iconHoverBtn = document.querySelector('#switcher-icon-hover');
    closedBtn = document.querySelector('#switcher-closed-menu');
    iconTextBtn = document.querySelector('#switcher-icontext-menu');
    overlayBtn = document.querySelector('#switcher-icon-overlay');
    doubleBtn = document.querySelector('#switcher-double-menu');
    detachedBtn = document.querySelector('#switcher-detached');
    resetBtn = document.querySelector('#resetbtn');
    primaryDefaultColor1Btn = document.querySelector('#switcher-primary');
    primaryDefaultColor2Btn = document.querySelector('#switcher-primary1');
    primaryDefaultColor3Btn = document.querySelector('#switcher-primary2');
    primaryDefaultColor4Btn = document.querySelector('#switcher-primary3');
    primaryDefaultColor5Btn = document.querySelector('#switcher-primary4');
    bgDefaultColor1Btn = document.querySelector('#switcher-background');
    bgDefaultColor2Btn = document.querySelector('#switcher-background1');
    bgDefaultColor3Btn = document.querySelector('#switcher-background2');
    bgDefaultColor4Btn = document.querySelector('#switcher-background3');
    bgDefaultColor5Btn = document.querySelector('#switcher-background4');
    bgImage1Btn = document.querySelector('#switcher-bg-img');
    bgImage2Btn = document.querySelector('#switcher-bg-img1');
    bgImage3Btn = document.querySelector('#switcher-bg-img2');
    bgImage4Btn = document.querySelector('#switcher-bg-img3');
    bgImage5Btn = document.querySelector('#switcher-bg-img4');
    ResetAll = document.querySelector('#reset-all');
    loaderEnable = document.querySelector('#switcher-loader-enable');
    loaderDisable = document.querySelector('#switcher-loader-disable');

    // primary theme
    let primaryColor1Var = primaryDefaultColor1Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "171, 125, 241");
        html.style.setProperty('--primary-rgb', `171, 125, 241`);
    })
    let primaryColor2Var = primaryDefaultColor2Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "69, 184, 224");
        html.style.setProperty('--primary-rgb', `69, 184, 224`);
    })
    let primaryColor3Var = primaryDefaultColor3Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "231, 105, 182");
        html.style.setProperty('--primary-rgb', `231, 105, 182`);
    })
    let primaryColor4Var = primaryDefaultColor4Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "26, 139, 114");
        html.style.setProperty('--primary-rgb', `26, 139, 114`);
    })
    let primaryColor5Var = primaryDefaultColor5Btn.addEventListener('click', () => {
        localStorage.setItem("primaryRGB", "10, 106, 169");
        html.style.setProperty('--primary-rgb', `10, 106, 169`);
    })

    // Background theme
    let backgroundColor1Var = bgDefaultColor1Btn.addEventListener('click', () => {
        localStorage.setItem('bodyBgRGB', "70, 40, 115");
        localStorage.setItem('bodylightRGB', "84, 54, 129");
        html.setAttribute('data-theme-mode', 'dark');
        html.setAttribute('data-menu-styles', 'dark');
        html.setAttribute('data-header-styles', 'dark');
        document.querySelector('html').style.setProperty('--body-bg-rgb', localStorage.bodyBgRGB);
        document.querySelector('html').style.setProperty('--body-bg-rgb2', localStorage.bodylightRGB);
        document.querySelector('html').style.setProperty('--light-rgb', "84, 54, 129");
        document.querySelector('html').style.setProperty('--form-control-bg', "rgb(84, 54, 129)");
        document.querySelector('html').style.setProperty('--input-border', "rgba(255,255,255,0.1)");
        document.querySelector('html').style.setProperty('--gray-3', "rgba(255,255,255,0.1)");
        document.querySelector('#switcher-dark-theme').checked = true;
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelector('#switcher-header-dark').checked = true;
        localStorage.setItem("rixzoMenu", "dark");
        localStorage.setItem("rixzoHeader", "dark");
    })
    let backgroundColor2Var = bgDefaultColor2Btn.addEventListener('click', () => {
        localStorage.setItem('bodyBgRGB', "32, 87, 105");
        localStorage.setItem('bodylightRGB', "46, 101, 119");
        html.setAttribute('data-theme-mode', 'dark');
        html.setAttribute('data-menu-styles', 'dark');
        html.setAttribute('data-header-styles', 'dark');
        document.querySelector('html').style.setProperty('--body-bg-rgb', localStorage.bodyBgRGB);
        document.querySelector('html').style.setProperty('--body-bg-rgb2', localStorage.bodylightRGB);
        document.querySelector('html').style.setProperty('--light-rgb', "46, 101, 119");
        document.querySelector('html').style.setProperty('--form-control-bg', "rgb(46, 101, 119)");
        document.querySelector('html').style.setProperty('--input-border', "rgba(255,255,255,0.1)");
        document.querySelector('html').style.setProperty('--gray-3', "rgba(255,255,255,0.1)");
        document.querySelector('#switcher-dark-theme').checked = true;
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelector('#switcher-header-dark').checked = true;
        localStorage.setItem("rixzoMenu", "dark");
        localStorage.setItem("rixzoHeader", "dark");
    })
    let backgroundColor3Var = bgDefaultColor3Btn.addEventListener('click', () => {
        localStorage.setItem('bodyBgRGB', "105, 40, 83");
        localStorage.setItem('bodylightRGB', "119, 54, 97");
        html.setAttribute('data-theme-mode', 'dark');
        html.setAttribute('data-menu-styles', 'dark');
        html.setAttribute('data-header-styles', 'dark');
        document.querySelector('html').style.setProperty('--body-bg-rgb', localStorage.bodyBgRGB);
        document.querySelector('html').style.setProperty('--body-bg-rgb2', localStorage.bodylightRGB);
        document.querySelector('html').style.setProperty('--light-rgb', "119, 54, 97");
        document.querySelector('html').style.setProperty('--form-control-bg', "rgb(119, 54, 97)");
        document.querySelector('html').style.setProperty('--input-border', "rgba(255,255,255,0.1)");
        document.querySelector('html').style.setProperty('--gray-3', "rgba(255,255,255,0.1)");
        document.querySelector('#switcher-dark-theme').checked = true;
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelector('#switcher-header-dark').checked = true;
        localStorage.setItem("rixzoMenu", "dark");
        localStorage.setItem("rixzoHeader", "dark");
    })
    let backgroundColor4Var = bgDefaultColor4Btn.addEventListener('click', () => {
        localStorage.setItem('bodyBgRGB', "0, 56, 51");
        localStorage.setItem('bodylightRGB', "14, 70, 65");
        html.setAttribute('data-theme-mode', 'dark');
        html.setAttribute('data-menu-styles', 'dark');
        html.setAttribute('data-header-styles', 'dark');
        document.querySelector('html').style.setProperty('--body-bg-rgb', localStorage.bodyBgRGB);
        document.querySelector('html').style.setProperty('--body-bg-rgb2', localStorage.bodylightRGB);
        document.querySelector('html').style.setProperty('--light-rgb', "14, 70, 65");
        document.querySelector('html').style.setProperty('--form-control-bg', "rgb(14, 70, 65)");
        document.querySelector('html').style.setProperty('--input-border', "rgba(255,255,255,0.1)");
        document.querySelector('html').style.setProperty('--gray-3', "rgba(255,255,255,0.1)");
        document.querySelector('#switcher-dark-theme').checked = true;
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelector('#switcher-header-dark').checked = true;
        localStorage.setItem("rixzoMenu", "dark");
        localStorage.setItem("rixzoHeader", "dark");
    })
    let backgroundColor5Var = bgDefaultColor5Btn.addEventListener('click', () => {
        localStorage.setItem('bodyBgRGB', "5, 53, 100");
        localStorage.setItem('bodylightRGB', "19, 67, 114");
        html.setAttribute('data-theme-mode', 'dark');
        html.setAttribute('data-menu-styles', 'dark');
        html.setAttribute('data-header-styles', 'dark');
        document.querySelector('html').style.setProperty('--body-bg-rgb', localStorage.bodyBgRGB);
        document.querySelector('html').style.setProperty('--body-bg-rgb2', localStorage.bodylightRGB);
        document.querySelector('html').style.setProperty('--light-rgb', "19, 67, 114");
        document.querySelector('html').style.setProperty('--form-control-bg', "rgb(19, 67, 114)");
        document.querySelector('html').style.setProperty('--input-border', "rgba(255,255,255,0.1)");
        document.querySelector('html').style.setProperty('--gray-3', "rgba(255,255,255,0.1)");
        document.querySelector('#switcher-dark-theme').checked = true;
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelector('#switcher-header-dark').checked = true;
        localStorage.setItem("rixzoMenu", "dark");
        localStorage.setItem("rixzoHeader", "dark");
    })

    // Bg image
    let bgImg1Var = bgImage1Btn.addEventListener('click', () => {
        html.setAttribute('data-bg-img', 'bgimg1')
        localStorage.setItem("bgimg", "bgimg1")
    })
    let bgImg2Var = bgImage2Btn.addEventListener('click', () => {
        html.setAttribute('data-bg-img', 'bgimg2')
        localStorage.setItem("bgimg", "bgimg2")
    })
    let bgImg3Var = bgImage3Btn.addEventListener('click', () => {
        html.setAttribute('data-bg-img', 'bgimg3')
        localStorage.setItem("bgimg", "bgimg3")
    })
    let bgImg4Var = bgImage4Btn.addEventListener('click', () => {
        html.setAttribute('data-bg-img', 'bgimg4')
        localStorage.setItem("bgimg", "bgimg4")
    })
    let bgImg5Var = bgImage5Btn.addEventListener('click', () => {
        html.setAttribute('data-bg-img', 'bgimg5')
        localStorage.setItem("bgimg", "bgimg5")
    })

    /* Light Layout Start */
    let lightThemeVar = lightBtn.addEventListener('click', () => {
        lightFn();
        localStorage.setItem("rixzoHeader", 'light');
        localStorage.removeItem("bodylightRGB")
        localStorage.removeItem("bodyBgRGB")
        localStorage.removeItem("rixzoMenu")
        if (html.getAttribute('data-nav-layout') === 'horizontal') {
            html.setAttribute('data-header-styles', 'light');
        }
    })
    /* Light Layout End */

    /* Dark Layout Start */
    let darkThemeVar = darkBtn.addEventListener('click', () => {
        darkFn();
        localStorage.setItem("rixzoMenu", 'dark');
        localStorage.setItem("rixzoHeader", 'dark');
        if (html.getAttribute('data-nav-layout') === 'horizontal') {
            html.setAttribute('data-header-styles', 'dark');
        }
    });
    /* Dark Layout End */

    /* Light Menu Start */
    let lightMenuVar = lightMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-styles', 'light');
        localStorage.setItem("rixzoMenu", 'light');
    });
    /* Light Menu End */

    /* Color Menu Start */
    let colorMenuVar = colorMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-styles', 'color');
        localStorage.setItem("rixzoMenu", 'color');
    });
    /* Color Menu End */

    /* Dark Menu Start */
    let darkMenuVar = darkMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-styles', 'dark');
        localStorage.setItem("rixzoMenu", 'dark');
    });
    /* Dark Menu End */

    /* Gradient Menu Start */
    let gradientMenuVar = gradientMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-styles', 'gradient');
        localStorage.setItem("rixzoMenu", 'gradient');
    });
    /* Gradient Menu End */

    /* Transparent Menu Start */
    let transparentMenuVar = transparentMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-styles', 'transparent');
        localStorage.setItem("rixzoMenu", 'transparent');
    });
    /* Transparent Menu End */

    /* Light Header Start */
    let lightHeaderVar = lightHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-styles', 'light');
        localStorage.setItem("rixzoHeader", 'light');
    });
    /* Light Header End */

    /* Color Header Start */
    let colorHeaderVar = colorHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-styles', 'color');
        localStorage.setItem("rixzoHeader", 'color');
    });
    /* Color Header End */

    /* Dark Header Start */
    let darkHeaderVar = darkHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-styles', 'dark');
        localStorage.setItem("rixzoHeader", 'dark');
    });
    /* Dark Header End */

    /* Gradient Header Start */
    let gradientHeaderVar = gradientHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-styles', 'gradient');
        localStorage.setItem("rixzoHeader", 'gradient');
    });
    /* Gradient Header End */

    /* Transparent Header Start */
    let transparentHeaderVar = transparentHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-styles', 'transparent');
        localStorage.setItem("rixzoHeader", 'transparent');
    });
    /* Transparent Header End */

    /* Full Width Layout Start */
    let fullwidthVar = fullwidthBtn.addEventListener('click', () => {
        html.setAttribute('data-width', 'fullwidth');
        localStorage.setItem("rixzofullwidth", true);
        localStorage.removeItem("rixzoboxed");
    });
    /* Full Width Layout End */

    /* Boxed Layout Start */
    let boxedVar = boxedBtn.addEventListener('click', () => {
        html.setAttribute('data-width', 'boxed');
        localStorage.setItem("rixzoboxed", true);
        localStorage.removeItem("rixzofullwidth");
        checkHoriMenu()
    });
    /* Boxed Layout End */

    /* Regular page style Start */
    let shadowVar = regular.addEventListener('click', () => {
        html.setAttribute('data-page-style', 'regular');
        localStorage.setItem("rixzoregular", true);
        localStorage.removeItem("rixzoclassic");
        localStorage.removeItem("rixzomodern");
    });
    /* Regular page style End */

    /* Classic page style Start */
    let noShadowVar = classic.addEventListener('click', () => {
        html.setAttribute('data-page-style', 'classic');
        localStorage.setItem("rixzoclassic", true);
        localStorage.removeItem("rixzoregular");
        localStorage.removeItem("rixzomodern");
    });
    /* Classic page style End */

    /* modern page style Start */
    let modernVar = modern.addEventListener('click', () => {
        html.setAttribute('data-page-style', 'modern');
        localStorage.setItem("rixzomodern", true);
        localStorage.removeItem("rixzoregular");
        localStorage.removeItem("rixzoclassic");
    });
    /* modern page style End */

    /* Header-Position Styles Start */
    let fixedHeaderVar = fixedHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-position', 'fixed');
        localStorage.setItem("rixzoheaderfixed", true);
        localStorage.removeItem("rixzoheaderscrollable");
    });

    let scrollHeaderVar = scrollHeaderBtn.addEventListener('click', () => {
        html.setAttribute('data-header-position', 'scrollable');
        localStorage.setItem("rixzoheaderscrollable", true);
        localStorage.removeItem("rixzoheaderfixed");
    });
    /* Header-Position Styles End */

    /* Menu-Position Styles Start */
    let fixedMenuVar = fixedMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-position', 'fixed');
        localStorage.setItem("rixzomenufixed", true);
        localStorage.removeItem("rixzomenuscrollable");
    });

    let scrollMenuVar = scrollMenuBtn.addEventListener('click', () => {
        html.setAttribute('data-menu-position', 'scrollable');
        localStorage.setItem("rixzomenuscrollable", true);
        localStorage.removeItem("rixzomenufixed");
    });
    /* Menu-Position Styles End */

    /* Default Sidemenu Start */
    let defaultVar = defaultBtn.addEventListener('click', () => {
        html.setAttribute('data-vertical-style', 'default');
        html.setAttribute('data-nav-layout', 'vertical');
        localStorage.removeItem('rixzonavstyles');
        toggleSidemenu();
        localStorage.removeItem("rixzoverticalstyles");
        document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
            if (!ele.classList.contains('active')) {
                ele.classList.remove('open')
                ele.querySelector('ul').style.display = 'none'
            }
        })
    });
    /* Default Sidemenu End */

    /* Closed Sidemenu Start */
    let closedVar = closedBtn.addEventListener('click', () => {
        closedSidemenuFn();
        localStorage.setItem("rixzoverticalstyles", 'closed');
        document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
            if (!ele.classList.contains('active')) {
                ele.classList.remove('open')
                ele.querySelector('ul').style.display = 'none'
            }
        })
    });
    /* Closed Sidemenu End */

    /* Hover Submenu Start */
    let detachedVar = detachedBtn.addEventListener('click', () => {
        detachedFn();
        localStorage.setItem("rixzoverticalstyles", 'detached');
    });
    /* Hover Submenu End */

    /* Icon Text Sidemenu Start */
    let iconTextVar = iconTextBtn.addEventListener('click', () => {
        iconTextFn();
        localStorage.setItem("rixzoverticalstyles", 'icontext');
    });
    /* Icon Text Sidemenu End */

    /* Icon Overlay Sidemenu Start */
    let overlayVar = overlayBtn.addEventListener('click', () => {
        iconOverayFn();
        localStorage.setItem("rixzoverticalstyles", 'overlay');
        document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
            if (!ele.classList.contains('active')) {
                ele.classList.remove('open')
                ele.querySelector('ul').style.display = 'none'
            }
        })
    });
    /* Icon Overlay Sidemenu End */

    /* doublemenu Sidemenu Start */
    let doubleVar = doubleBtn.addEventListener('click', () => {
        doubletFn();
        localStorage.setItem("rixzoverticalstyles", 'doublemenu');
    });
    /* doublemenu Sidemenu End */

    /* Menu Click Sidemenu Start */
    let menuClickVar = menuClickBtn.addEventListener('click', () => {
        html.removeAttribute('data-vertical-style');
        menuClickFn();
        localStorage.setItem("rixzonavstyles", 'menu-click');
        localStorage.removeItem("rixzoverticalstyles");
        document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
            if (!ele.classList.contains('active')) {
                ele.classList.remove('open')
                ele.querySelector('ul').style.display = 'none'
            }
        })
        if (document.querySelector("html").getAttribute("data-nav-layout") == 'horizontal') {
            document.querySelector(".main-menu").style.marginLeft = "0px"
            document.querySelector(".main-menu").style.marginRight = "0px"
            ResizeMenu()
        }
    });
    /* Menu Click Sidemenu End */

    /* Menu Hover Sidemenu Start */
    let menuhoverVar = menuHoverBtn.addEventListener('click', () => {
        html.removeAttribute('data-vertical-style');
        menuhoverFn();
        localStorage.setItem("rixzonavstyles", 'menu-hover');
        localStorage.removeItem("rixzoverticalstyles");

        if (document.querySelector("html").getAttribute("data-nav-layout") == 'horizontal') {
            document.querySelector(".main-menu").style.marginLeft = "0px"
            document.querySelector(".main-menu").style.marginRight = "0px"
            ResizeMenu()
        }
    });
    /* Menu Hover Sidemenu End */

    /* icon Click Sidemenu Start */
    let iconClickVar = iconClickBtn.addEventListener('click', () => {
        html.removeAttribute('data-vertical-style');
        iconClickFn();
        localStorage.setItem("rixzonavstyles", 'icon-click');
        localStorage.removeItem("rixzoverticalstyles");

        if (document.querySelector("html").getAttribute("data-nav-layout") == 'horizontal') {
            document.querySelector(".main-menu").style.marginLeft = "0px"
            document.querySelector(".main-menu").style.marginRight = "0px"
            ResizeMenu()
            document.querySelector("#slide-left").classList.add("d-none")
        }
        document.querySelectorAll(".main-menu>li.open").forEach((ele) => {
            if (!ele.classList.contains('active')) {
                ele.classList.remove('open')
                ele.querySelector('ul').style.display = 'none'
            }
        })
    });
    /* icon Click Sidemenu End */

    /* icon hover Sidemenu Start */
    let iconhoverVar = iconHoverBtn.addEventListener('click', () => {
        html.removeAttribute('data-vertical-style');
        iconHoverFn();
        localStorage.setItem("rixzonavstyles", 'icon-hover');
        localStorage.removeItem("rixzoverticalstyles");

        if (document.querySelector("html").getAttribute("data-nav-layout") == 'horizontal') {
            document.querySelector(".main-menu").style.marginLeft = "0px"
            document.querySelector(".main-menu").style.marginRight = "0px"
            ResizeMenu()
            document.querySelector("#slide-left").classList.add("d-none")
        }
    });
    /* icon hover Sidemenu End */

    /* Sidemenu start*/
    let verticalVar = verticalBtn.addEventListener('click', () => {
        let mainContent = document.querySelector('.main-content');
        // local storage
        localStorage.removeItem("rixzolayout");
        localStorage.setItem("rixzoverticalstyles", 'default');
        verticalFn();
        setNavActive();
        mainContent.removeEventListener('click', clearNavDropdown);

        //
        document.querySelector(".main-menu").style.marginLeft = "0px"
        document.querySelector(".main-menu").style.marginRight = "0px"

        document.querySelectorAll(".slide").forEach((element) => {
            if (
                element.classList.contains("open") &&
                !element.classList.contains("active")
            ) {
                element.querySelector("ul").style.display = "none";
            }
        });
    });
    /* Sidemenu end */

    /* horizontal start*/
    let horiVar = horiBtn.addEventListener('click', () => {
        let mainContent = document.querySelector('.main-content');
        html.removeAttribute('data-vertical-style');
        //    local storage
        localStorage.setItem("rixzolayout", 'horizontal');
        localStorage.removeItem("rixzoverticalstyles");

        horizontalClickFn();
        clearNavDropdown();
        mainContent.addEventListener('click', clearNavDropdown);
    });
    /* horizontal end*/

    /* rtl start */
    let rtlVar = rtlBtn.addEventListener('click', () => {
        localStorage.setItem("rixzortl", true);
        localStorage.removeItem("rixzoltr");
        rtlFn();
        if (document.querySelector(".noUi-target")) {
            document.querySelectorAll(".noUi-origin").forEach((e) => {
                e.classList.add("transform-none");
            });
        }
    });
    /* rtl end */

    /* ltr start */
    let ltrVar = ltrBtn.addEventListener('click', () => {
        //    local storage
        localStorage.setItem("rixzoltr", true);
        localStorage.removeItem("rixzortl");
        ltrFn();
        if (document.querySelector(".noUi-target")) {
            document.querySelectorAll(".noUi-origin").forEach((e) => {
                e.classList.remove("transform-none");
            });
        }
    });
    /* ltr end */

    // reset all start
    let resetVar = ResetAll.addEventListener('click', () => {
        ResetAllFn();
        setNavActive();
        document.querySelector("html").setAttribute("data-menu-styles", "dark");
        document.querySelector("html").setAttribute("data-width", "fullwidth");
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelectorAll(".slide").forEach((element) => {
            if (
                element.classList.contains("open") &&
                !element.classList.contains("active")
            ) {
                element.querySelector("ul").style.display = "none";
            }
        });
        if (document.querySelector(".noUi-target")) {
            document.querySelectorAll(".noUi-origin").forEach((e) => {
                e.classList.add("transform-none");
            });
        }
    })
    // reset all end

    /* loader start */
    loaderEnable.onclick = () => {
        document.querySelector("html").setAttribute("loader", "enable");
        localStorage.setItem("loaderEnable", "true")
    }

    loaderDisable.onclick = () => {
        document.querySelector("html").setAttribute("loader", "disable");
        localStorage.setItem("loaderEnable", "false")
    }
    /* loader end */
}

function ltrFn() {
    let html = document.querySelector('html')
    if (!document.querySelector("#style").href.includes('bootstrap.min.css')) {
        document.querySelector("#style")?.setAttribute("href", "../assets/libs/bootstrap/css/bootstrap.min.css");
    }
    html.setAttribute("dir", "ltr");
    document.querySelector('#switcher-ltr').checked = true;
    checkOptions();
}

function rtlFn() {
    let html = document.querySelector('html');
    html.setAttribute("dir", "rtl");
    document.querySelector("#style")?.setAttribute("href", "../assets/libs/bootstrap/css/bootstrap.rtl.min.css");
    checkOptions();
}

function lightFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-theme-mode', 'light');
    html.setAttribute('data-header-styles', 'light');
    html.setAttribute('data-menu-styles', 'dark');
    if (!localStorage.getItem('primaryRGB')) {
        html.setAttribute('style', '')
    }
    document.querySelector('#switcher-light-theme').checked = true;
    document.querySelector('#switcher-menu-light').checked = true;
    document.querySelector('#switcher-header-light').checked = true;
    localStorage.removeItem("rixzodarktheme");
    localStorage.removeItem("rixzobgColor");
    localStorage.removeItem("rixzoheaderbg");
    localStorage.removeItem("rixzobgwhite");
    localStorage.removeItem("rixzomenubg");
    localStorage.removeItem("rixzomenubg");
    checkOptions();
    html.style.removeProperty('--body-bg-rgb');
    html.style.removeProperty('--body-bg-rgb2');
    html.style.removeProperty("--light-rgb");
    html.style.removeProperty("--form-control-bg");
    html.style.removeProperty("--gray-3");
    html.style.removeProperty("--input-border");

    document.querySelector("#switcher-background4").checked = false
    document.querySelector("#switcher-background3").checked = false
    document.querySelector("#switcher-background2").checked = false
    document.querySelector("#switcher-background1").checked = false
    document.querySelector("#switcher-background").checked = false
    document.querySelector('#switcher-menu-dark').checked = true;
    document.querySelector('#switcher-header-light').checked = true;

}

function darkFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-theme-mode', 'dark');
    html.setAttribute('data-header-styles', 'dark');
    html.setAttribute('data-menu-styles', 'dark');
    if (!localStorage.getItem('primaryRGB')) {
        html.setAttribute('style', '')
    }
    document.querySelector('#switcher-menu-dark').checked = true;
    document.querySelector('#switcher-header-dark').checked = true;
    document.querySelector('html').style.removeProperty('--body-bg-rgb');
    document.querySelector('html').style.removeProperty('--body-bg-rgb2');
    document.querySelector('html').style.removeProperty('--light-rgb');
    document.querySelector('html').style.removeProperty('--form-control-bg');
    document.querySelector('html').style.removeProperty('--gray-3');
    document.querySelector('html').style.removeProperty('--input-border');
    localStorage.setItem("rixzodarktheme", true);
    localStorage.removeItem("rixzolighttheme");
    localStorage.removeItem("bodyBgRGB");
    localStorage.removeItem("rixzobgColor");
    localStorage.removeItem("rixzoheaderbg");
    localStorage.removeItem("rixzobgwhite");
    localStorage.removeItem("rixzomenubg");
    checkOptions();

    document.querySelector("#switcher-background4").checked = false
    document.querySelector("#switcher-background3").checked = false
    document.querySelector("#switcher-background2").checked = false
    document.querySelector("#switcher-background1").checked = false
    document.querySelector("#switcher-background").checked = false
    document.querySelector('#switcher-menu-dark').checked = true;
    document.querySelector('#switcher-header-dark').checked = true;
}

function verticalFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'vertical');
    html.setAttribute('data-vertical-style', 'overlay');
    html.removeAttribute('data-nav-style');
    localStorage.removeItem('rixzonavstyles');
    html.removeAttribute('data-toggled');
    document.querySelector('#switcher-vertical').checked = true;
    document.querySelector('#switcher-menu-click').checked = false;
    document.querySelector('#switcher-menu-hover').checked = false;
    document.querySelector('#switcher-icon-click').checked = false;
    document.querySelector('#switcher-icon-hover').checked = false;
    checkOptions();
}

function horizontalClickFn() {
    document.querySelector('#switcher-horizontal').checked = true;
    document.querySelector('#switcher-menu-click').checked = true;
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'horizontal');
    html.removeAttribute('data-vertical-style');
    if (!html.getAttribute('data-nav-style')) {
        html.setAttribute('data-nav-style', 'menu-click');
    }
    if (!localStorage.rixzoMenu && !localStorage.bodylightRGB) {
        html.setAttribute("data-menu-styles", "dark")
        document.querySelector('#switcher-menu-dark').checked = true;
        checkOptions();
    }
    checkOptions();
    checkHoriMenu();
}


function ResetAllFn() {
    let html = document.querySelector('html');
    if (localStorage.getItem("rixzolayout") == "horizontal") {
        document.querySelector(".main-menu").style.display = "block"
    }
    checkOptions();

    // clearing localstorage
    localStorage.clear();

    // reseting to light
    lightFn();

    //To reset the light-rgb
    document.querySelector('html').removeAttribute("style")

    // clearing attibutes
    // removing header, menu, pageStyle & boxed
    html.removeAttribute('data-nav-style');
    html.removeAttribute('data-menu-position');
    html.removeAttribute('data-header-position');
    html.removeAttribute('data-page-style');

    // removing theme styles
    html.removeAttribute('data-bg-img');

    // clear primary & bg color
    html.style.removeProperty(`--primary-rgb`);
    html.style.removeProperty(`--body-bg-rgb`);

    // reseting to ltr
    ltrFn();

    // reseting to vertical
    verticalFn();
    mainContent.removeEventListener('click', clearNavDropdown);

    // reseting page style
    document.querySelector('#switcher-classic').checked = false;
    document.querySelector('#switcher-modern').checked = false;
    document.querySelector('#switcher-regular').checked = true;

    // reseting layout width styles
    document.querySelector('#switcher-full-width').checked = true;
    document.querySelector('#switcher-boxed').checked = false;

    // reseting menu position styles
    document.querySelector('#switcher-menu-fixed').checked = true;
    document.querySelector('#switcher-menu-scroll').checked = false;

    // reseting header position styles
    document.querySelector('#switcher-header-fixed').checked = true;
    document.querySelector('#switcher-header-scroll').checked = false;

    // reseting sidemenu layout styles
    document.querySelector('#switcher-default-menu').checked = true;
    document.querySelector('#switcher-closed-menu').checked = false;
    document.querySelector('#switcher-icontext-menu').checked = false;
    document.querySelector('#switcher-icon-overlay').checked = false;
    document.querySelector('#switcher-detached').checked = false;
    document.querySelector('#switcher-double-menu').checked = false;

    // resetting theme primary
    document.querySelector("#switcher-primary").checked = false
    document.querySelector("#switcher-primary1").checked = false
    document.querySelector("#switcher-primary2").checked = false
    document.querySelector("#switcher-primary3").checked = false
    document.querySelector("#switcher-primary4").checked = false

    // resetting theme background
    document.querySelector("#switcher-background").checked = false
    document.querySelector("#switcher-background1").checked = false
    document.querySelector("#switcher-background2").checked = false
    document.querySelector("#switcher-background3").checked = false
    document.querySelector("#switcher-background4").checked = false

    // to reset horizontal menu scroll
    document.querySelector(".main-menu").style.marginLeft = "0px"
    document.querySelector(".main-menu").style.marginRight = "0px"

    // setTimeout(() => {
    //     if (element && document.querySelector(".child3 .side-menu__item.active")) {
    //         element.closest('ul.slide-menu').style.display = "block"
    //         element.closest('ul.slide-menu').closest('li.slide.has-sub').classList.add('open')
    //         element.closest('ul.slide-menu').closest('li.slide.has-sub').querySelector('.side-menu__item').classList.add('active')
    //         element.closest('ul.slide-menu').closest('li.slide.has-sub').querySelector('.child2 .has-sub.active').classList.add("open")
    //     }
    // }, 100);
}

function checkOptions() {

    // dark
    if (localStorage.getItem('rixzodarktheme')) {
        document.querySelector('#switcher-dark-theme').checked = true;
    }

    // horizontal
    if (localStorage.getItem('rixzolayout') === "horizontal") {
        document.querySelector('#switcher-horizontal').checked = true;
        document.querySelector('#switcher-menu-click').checked = true;
    }
    else {
        document.querySelector('#switcher-vertical').checked = true;
    }

    //RTL
    if (localStorage.getItem('rixzortl')) {
        document.querySelector('#switcher-rtl').checked = true;
    }
    else {
        document.querySelector('#switcher-ltr').checked = true;
    }

    // light header
    if (localStorage.getItem('rixzoHeader') === "light") {
        document.querySelector('#switcher-header-light').checked = true;
    }

    // color header
    if (localStorage.getItem('rixzoHeader') === "color") {
        document.querySelector('#switcher-header-primary').checked = true;
    }

    // gradient header
    if (localStorage.getItem('rixzoHeader') === "gradient") {
        document.querySelector('#switcher-header-gradient').checked = true;
    }

    // dark header
    if (localStorage.getItem('rixzoHeader') === "dark") {
        document.querySelector('#switcher-header-dark').checked = true;
    }
    // transparent header
    if (localStorage.getItem('rixzoHeader') === "transparent") {
        document.querySelector('#switcher-header-transparent').checked = true;
    }

    // light menu
    if (localStorage.getItem('rixzoMenu') === 'light') {
        document.querySelector('#switcher-menu-light').checked = true;
    }

    // color menu
    if (localStorage.getItem('rixzoMenu') === 'color') {
        document.querySelector('#switcher-menu-primary').checked = true;
    }

    // gradient menu
    if (localStorage.getItem('rixzoMenu') === 'gradient') {
        document.querySelector('#switcher-menu-gradient').checked = true;
    }

    // dark menu
    if (localStorage.getItem('rixzoMenu') === 'dark') {
        document.querySelector('#switcher-menu-dark').checked = true;
    }
    // transparent menu
    if (localStorage.getItem('rixzoMenu') === 'transparent') {
        document.querySelector('#switcher-menu-transparent').checked = true;
    }

    //full width
    if (localStorage.getItem('rixzofullwidth')) {
        document.querySelector('#switcher-full-width').checked = true;
    }

    //boxed
    if (localStorage.getItem('rixzoboxed')) {
        document.querySelector('#switcher-boxed').checked = true;
    }

    //scrollable
    if (localStorage.getItem('rixzoheaderscrollable')) {
        document.querySelector('#switcher-header-scroll').checked = true;
    }
    if (localStorage.getItem('rixzomenuscrollable')) {
        document.querySelector('#switcher-menu-scroll').checked = true;
    }

    //fixed
    if (localStorage.getItem('rixzoheaderfixed')) {
        document.querySelector('#switcher-header-fixed').checked = true;
    }
    if (localStorage.getItem('rixzomenufixed')) {
        document.querySelector('#switcher-menu-fixed').checked = true;
    }

    //classic
    if (localStorage.getItem('rixzoclassic')) {
        document.querySelector('#switcher-classic').checked = true;
    }

    //modern
    if (localStorage.getItem('rixzomodern')) {
        document.querySelector('#switcher-modern').checked = true;
    }

    // sidemenu layout style
    if (localStorage.rixzoverticalstyles) {
        let verticalStyles = localStorage.getItem('rixzoverticalstyles');
        switch (verticalStyles) {
            case 'default':
                document.querySelector('#switcher-default-menu').checked = true;
                break;
            case 'closed':
                document.querySelector('#switcher-closed-menu').checked = true;
                break;
            case 'icontext':
                document.querySelector('#switcher-icontext-menu').checked = true;
                break;
            case 'overlay':
                document.querySelector('#switcher-icon-overlay').checked = true;
                break;
            case 'detached':
                document.querySelector('#switcher-detached').checked = true;
                break;
            case 'doublemenu':
                document.querySelector('#switcher-double-menu').checked = true;
                break;
            default:
                document.querySelector('#switcher-default-menu').checked = true;
                break;
        }
    }
    // navigation menu style
    if (localStorage.rixzonavstyles) {
        let navStyles = localStorage.getItem('rixzonavstyles');
        switch (navStyles) {
            case 'menu-click':
                document.querySelector('#switcher-menu-click').checked = true;
                break;
            case 'menu-hover':
                document.querySelector('#switcher-menu-hover').checked = true;
                break;
            case 'icon-click':
                document.querySelector('#switcher-icon-click').checked = true;
                break;
            case 'icon-hover':
                document.querySelector('#switcher-icon-hover').checked = true;
                break;
        }
    }

    // loader
    if (localStorage.loaderEnable != "true") {
        document.querySelector("#switcher-loader-disable").checked = true
    }
}

function localStorageBackup2() {
    if (localStorage.bodyBgRGB || localStorage.bodylightRGB) {
        document.querySelector('#switcher-dark-theme').checked = true;
        document.querySelector('#switcher-menu-dark').checked = true;
        document.querySelector('#switcher-header-dark').checked = true;
    }

    if (localStorage.bodyBgRGB && localStorage.bodylightRGB) {
        if (localStorage.bodyBgRGB == "70, 40, 115") {
            document.querySelector("#switcher-background").checked = true
        }
        if (localStorage.bodyBgRGB == "32, 87, 105") {
            document.querySelector("#switcher-background1").checked = true
        }
        if (localStorage.bodyBgRGB == "105, 40, 83") {
            document.querySelector("#switcher-background2").checked = true
        }
        if (localStorage.bodyBgRGB == "0, 56, 51") {
            document.querySelector("#switcher-background3").checked = true
        }
        if (localStorage.bodyBgRGB == "5, 53, 100") {
            document.querySelector("#switcher-background4").checked = true
        }
    }

    if (localStorage.primaryRGB) {
        if (localStorage.primaryRGB == "171, 125, 241") {
            document.querySelector("#switcher-primary").checked = true
        }
        if (localStorage.primaryRGB == "69, 184, 224") {
            document.querySelector("#switcher-primary1").checked = true
        }
        if (localStorage.primaryRGB == "231, 105, 182") {
            document.querySelector("#switcher-primary2").checked = true
        }
        if (localStorage.primaryRGB == "26, 139, 114") {
            document.querySelector("#switcher-primary3").checked = true
        }
        if (localStorage.primaryRGB == "10, 106, 169") {
            document.querySelector("#switcher-primary4").checked = true
        }
    }

    if (localStorage.loaderEnable == "true") {
        document.querySelector("#switcher-loader-enable").checked = true
    }
}


if (document.querySelector("#switcher-canvas")) {

    //switcher color pickers
    const pickrContainerPrimary = document.querySelector(
        ".pickr-container-primary"
    );
    const themeContainerPrimary = document.querySelector(
        ".theme-container-primary"
    );
    const pickrContainerBackground = document.querySelector(
        ".pickr-container-background"
    );
    const themeContainerBackground = document.querySelector(
        ".theme-container-background"
    );

    /* for theme primary */
    const nanoThemes = [
        [
        "nano",
        {
            defaultRepresentation: "RGB",
            components: {
            preview: true,
            opacity: false,
            hue: true,

            interaction: {
                hex: false,
                rgba: true,
                hsva: false,
                input: true,
                clear: false,
                save: false,
            },
            },
        },
        ],
    ];
    const nanoButtons = [];
    let nanoPickr = null;
    for (const [theme, config] of nanoThemes) {
        const button = document.createElement("button");
        button.innerHTML = theme;
        nanoButtons.push(button);

        button.addEventListener("click", () => {
        const el = document.createElement("p");
        pickrContainerPrimary.appendChild(el);

        /* Delete previous instance */
        if (nanoPickr) {
            nanoPickr.destroyAndRemove();
        }

        /* Apply active class */
        for (const btn of nanoButtons) {
            btn.classList[btn === button ? "add" : "remove"]("active");
        }

        /* Create fresh instance */
        nanoPickr = new Pickr(
            Object.assign(
            {
                el,
                theme,
                default: "#5c67f7",
            },
            config
            )
        );

        /* Set events */
        nanoPickr.on("changestop", (source, instance) => {
            let color = instance.getColor().toRGBA();
            let html = document.querySelector("html");
            html.style.setProperty(
            "--primary-rgb",
            `${Math.floor(color[0])}, ${Math.floor(color[1])}, ${Math.floor(
                color[2]
            )}`
            );
            /* theme color picker */
            localStorage.setItem(
            "primaryRGB",
            `${Math.floor(color[0])}, ${Math.floor(color[1])}, ${Math.floor(
                color[2]
            )}`
            );
            // updateColors();
        });
        });

        themeContainerPrimary.appendChild(button);
    }
    nanoButtons[0].click();
    /* for theme primary */

    /* for theme background */
    const nanoThemes1 = [
        [
        "nano",
        {
            defaultRepresentation: "RGB",
            components: {
            preview: true,
            opacity: false,
            hue: true,

            interaction: {
                hex: false,
                rgba: true,
                hsva: false,
                input: true,
                clear: false,
                save: false,
            },
            },
        },
        ],
    ];
    const nanoButtons1 = [];
    let nanoPickr1 = null;
    for (const [theme, config] of nanoThemes) {
        const button = document.createElement("button");
        button.innerHTML = theme;
        nanoButtons1.push(button);

        button.addEventListener("click", () => {
        const el = document.createElement("p");
        pickrContainerBackground.appendChild(el);

        /* Delete previous instance */
        if (nanoPickr1) {
            nanoPickr1.destroyAndRemove();
        }

        /* Apply active class */
        for (const btn of nanoButtons) {
            btn.classList[btn === button ? "add" : "remove"]("active");
        }

        /* Create fresh instance */
        nanoPickr1 = new Pickr(
            Object.assign(
            {
                el,
                theme,
                default: "#5c67f7",
            },
            config
            )
        );

        /* Set events */
        nanoPickr1.on("changestop", (source, instance) => {
            let color = instance.getColor().toRGBA();
            let html = document.querySelector("html");
            html.style.setProperty(
            "--body-bg-rgb",
            `${color[0]}, ${color[1]}, ${color[2]}`
            );
            document
            .querySelector("html")
            .style.setProperty(
                "--body-bg-rgb2",
                `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`
            );
            document
            .querySelector("html")
            .style.setProperty(
                "--light-rgb",
                `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`
            );
            document
            .querySelector("html")
            .style.setProperty(
                "--form-control-bg",
                `rgb(${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14})`
            );
            document
                .querySelector("html")
                .style.setProperty(
                "--gray-3",
                `rgb(${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14})`
                );
            localStorage.removeItem("bgtheme");
            // updateColors();
            html.setAttribute("data-theme-mode", "dark");
            html.setAttribute("data-menu-styles", "dark");
            html.setAttribute("data-header-styles", "dark");
            localStorage.removeItem("rixzoHeader", 'light');
            document.querySelector("#switcher-dark-theme").checked = true;
            localStorage.setItem(
            "bodyBgRGB",
            `${color[0]}, ${color[1]}, ${color[2]}`
            );
            localStorage.setItem(
            "bodylightRGB",
            `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`
            );
        });
        });
        themeContainerBackground.appendChild(button);
    }
    nanoButtons1[0].click();
    /* for theme background */

}