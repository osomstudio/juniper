import React, { useState } from "react"
import AlternatingResult from "./AlternatingResult"
import ArticleResult from "./ArticleResult"
import axios from 'axios'

const Filter = ( data ) => {
    const [selectedFilterVals, setSelectedFilterVals] = useState([])

    const updateFilterVals = (e, term_id) => {
        e.preventDefault()
        let shallowFilterVals = {...selectedFilterVals}
        if(shallowFilterVals.includes(term_id)) {
            shallowFilterVals = shallowFilterVals.splice(shallowFilterVals.indexOf(term_id), 1)
        } else {
            shallowFilterVals.push(term_id)
        }

        setSelectedFilterVals(shallowFilterVals)
    }

    // useEffect(() => {
    //     axios.get(`/`)
    // }, selectedFilterVals)

    return (
        <div className="w-full">
            <div className="filter-choices min-h-[400px] relative text-center text-white py-20">
                <div className="container mx-auto">
                    <h3 className="text-white">Filter</h3>
                    <div className="grid grid-cols-4 gap-8 justify-center">
                        {data.terms.map((term, index) => {
                            return (
                                <button key={index} className="filter-btn w-fit" type="button" onClick={(e) => updateFilterVals(e, term.term_id)}>{term.name}</button>
                            )
                        })}
                    </div>
                </div>
                <div className="background bg-dark"></div>
                <div className="absolute decoration left-0 top-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="89" height="1066" viewBox="0 0 89 1066" fill="none">
                        <path d="M-202 1065.32L88.3704 282.471L-176.427 -2.45403e-05L-202 1065.32Z" fill="#B4D43D" fillOpacity="0.6"/>
                    </svg>
                </div>
            </div>
            <div className="w-full relative">
                {data.posts.map((post, index) => {

                    if(data.style === "alternating") {
                        return <AlternatingResult key={index} index={index} post={post} />
                    } 

                    if(data.style === "article") {
                        return <ArticleResult key={index} index={index} post={post} />
                    }
                    return (
                        <div key={index}>
                            <h3>{post.post_title}</h3>
                        </div>
                    )
                })}
            </div>
        </div>
    )
}

export default Filter