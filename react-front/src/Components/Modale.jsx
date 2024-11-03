import React, { useEffect } from 'react'

const Modale = ({ isModaleOpen, setIsModaleOpen, modaleProblematic }) => {

    const handleClickOutside = (event) => {
        if (event.target.classList.contains('modale-overlay')) {
            setIsModaleOpen(false)
        }
    }

    const handleClickNo = () => {
        setIsModaleOpen(false)
    }

    return (
        <div
            onClick={handleClickOutside}
            className={isModaleOpen ? 'flex flex-col items-center justify-center text-primary bg-base-100 h-full w-full rounded-xl modale-overlay' : 'hidden'}>
            <div className='modale flex flex-col p-5 bg-base-200 rounded-xl gap-2 modale-shadow shadow-white items-center justify-center'>
                <p className='text-xl flex items-center justify-center'>
                    {modaleProblematic}
                </p>

                <div className='flex items-center justify-center p-2 gap-2'>

                    <button onClick={handleClickNo} className='btn btn-success flex-grow max-w-[150px]'>
                        Yes
                    </button>

                    <button onClick={handleClickNo} className='btn btn-error flex-grow max-w-[150px]'>
                        No
                    </button>

                </div>

            </div>
        </div>
    )
}

export default Modale
