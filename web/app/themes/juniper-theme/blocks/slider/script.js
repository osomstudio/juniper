
let slideNum = 1,
    totalNumSlides = document.querySelectorAll(".timeline-slide").length
    sliderBtn = document.getElementById("sliderScroll")
sliderBtn.addEventListener("click", () => {
    console.log('click', totalNumSlides)
    // get the next child in the parent
    if(slideNum === totalNumSlides) {
        slideNum = 1
    } else {
        slideNum++
    }
    console.log(slideNum)
    let targetSlide = document.querySelector(`.timeline-slider [data-slide-num='${slideNum}']`)
    console.log(targetSlide)
    targetSlide.scrollIntoView({ behavior: "smooth", block: "start", inline: "nearest" })
})
