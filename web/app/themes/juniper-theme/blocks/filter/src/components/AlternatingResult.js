import React, {useState} from "react"

const AlternatingResult = ({ index, post }) => {
    return (
        <div className={`w-full min-h-[600px] mb-40 inline-flex ${index % 2 === 0 ? 'even flex-row' : 'odd flex-row-reverse'}`}>
            
            <div className="w-1/2">
                <div className="teaser-image absolute z-0">
                    <div className="absolute decoration"></div>
                    <img className="absolute" src={post.fields.teaser_image} />
                </div>
                <div className="showcase-image z-10 relative">
                    <img className="max-w-[300px]" alt="Showcase Image" src={post.fields.showcase_image} />
                </div>
            </div>
            <div className="w-1/2 flex flex-col justify-center">
                <h3>{post.post_title} // {post.fields.year}</h3>
                <p className="mb-10">{post.terms.map(term => term.name).join(" // ")}</p>
                <div className="mb-20">
                    {post.excerpt}
                </div>
                <a className="btn-underline" href={`/${post.post_type}/${post.post_name}`}>
                    Mehr über {post.post_title}
                </a>
            </div>
           
        </div>
    )
}

export default AlternatingResult
