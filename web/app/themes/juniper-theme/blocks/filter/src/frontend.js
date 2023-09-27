import React from "react"
import Filter from "./components/Filter"
import ReactDOM from 'react-dom'


const filterDivs = document.querySelectorAll(".filter-update-me")

filterDivs.forEach(div => {
    let data = JSON.parse(div.dataset.initialData)
    console.log('filter data', data.posts)
    const root = ReactDOM.createRoot(div)
    root.render(<Filter {...data} />)
    div.classList.remove("filter-update-me")
})

