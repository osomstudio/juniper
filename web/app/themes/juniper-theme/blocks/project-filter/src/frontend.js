import React from "react"
import ProjectFilter from "./components/ProjectFilter"
import ReactDOM from 'react-dom'


const projectFilterDivs = document.querySelectorAll(".project-filter-update-me")

projectFilterDivs.forEach(div => {
    let data = projectFilterData
    console.log('project filter data', data)
    const root = ReactDOM.createRoot(div)
    root.render(<ProjectFilter {...data} />)
    div.classList.remove("project-filter-update-me")
})

