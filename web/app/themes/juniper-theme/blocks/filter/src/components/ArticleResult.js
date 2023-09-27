import React, {useState} from "react"

const ArticleResult = ({ index, post }) => {

    return (
        <div className={`flex-row ${index % 2 === 0 ? 'row-reverse' : ''}`}>
            <h3>{post.post_title} // 2022</h3>
        </div>
    )
}

export default ArticleResult
