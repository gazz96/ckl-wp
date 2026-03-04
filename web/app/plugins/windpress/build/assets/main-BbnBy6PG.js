import"./dist-DEatGUTy.min.js";import"./runtime-core.esm-bundler-oUzOcJDx.min.js";import"./vue.runtime.esm-bundler-zrGjzb-b.min.js";import"./core-CM2IDBdF.min.js";import"./isObject-BIXYRKoS.min.js";import"./_toKey-Bx4Bju8b.min.js";import"./_MapCache-CEiDyXI1.min.js";import"./set-Br-JnhMO.min.js";import"./_isIndex-1gFRY1uN.min.js";import"./get-Cil2H5_k.min.js";import{t as o}from"./virtualRef-DYSQzXPB.js";import{t as r}from"./logger-P3tS6JnT.js";import{t as n}from"./windpress-BUHj40mA.min.js";var s=document.createRange().createContextualFragment(`
    <button id="windpressbuilderius-settings-navbar" data-tooltip-content="WindPress \u2014 Builderius settings" data-tooltip-place="bottom" class="uniPanelButton">
        <span class="">
            ${n}
        </span>
    </button>
`),{getVirtualRef:i}=o({},{persist:"windpress.ui.state"});document.querySelector(".uniTopPanel__rightCol").prepend(s);var e=document.querySelector("#windpressbuilderius-settings-navbar");function a(){let t=i("window.minimized",!1).value;i("window.minimized",!1).value=!t,t?e.classList.add("active"):e.classList.remove("active")}e.addEventListener("click",t=>{a()}),r("Module loaded!",{module:"settings"});
