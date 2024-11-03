import React from 'react'
import LoaderBis from './LoaderBis'

const Loader2 = () => {
    return (
        <div direction="down" duration={2000} className='h-full w-full flex items-center justify-center  bg-opacity-70 rounded-xl'>
            <div className='h-full w-full flex flex-col items-center gap-5 ms-center justify-center bg-opacity-70 rounded-xl'>
                <p className='loader3'></p>
                <p>LOADING...</p>
            </div>
        </div>
    )
}

export default Loader2
