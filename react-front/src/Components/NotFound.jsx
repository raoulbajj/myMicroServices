import React from 'react'
import { Link } from 'react-router-dom'

const NotFound = () => {
    return (
        <div className='flex flex-grow w-full items-center justify-center text-4xl text-center'>

            <div className='flex flex-col gap-10 items-center justify-center'>

                <div className='flex flex-col items-center justify-center gap-5 background-title-animation'>

                    <span className='text-primary text-6xl raoul title-animation'>
                        404
                    </span>

                    <span className='title-animation text-3xl'>
                        NOT FOUND
                    </span>

                </div>


                <Link to='/' className='btn btn-primary btn-outline text-2xl w-fit hover:scale-105'>Go back Home</Link>
            </div>

        </div>
    )
}

export default NotFound
