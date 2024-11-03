import React from 'react'

const ChatMenuBtn = ({ setLoggedIn, loggedIn, modaleProblematic, setModaleProblematic, setIsModaleOpen }) => {

    const logMeOut = () => {
        setLoggedIn(!loggedIn)
    }

    const handleClickBtnProblematic = (event) => {
        setModaleProblematic(event.currentTarget.textContent)
        setIsModaleOpen(true)
    }

    return (
        <div className='flex items-center justify-center gap-2 flex-wrap w-fit'>


            <button
                onClick={handleClickBtnProblematic}
                className='btn btn-primary btn-outline'>
                New discussion
            </button>

            <button
                onClick={handleClickBtnProblematic}
                className='btn btn-success btn-outline'>
                Add User
            </button>

            <button
                onClick={handleClickBtnProblematic}
                className='btn btn-warning btn-outline'>
                Update discussion
            </button>

            <button
                onClick={handleClickBtnProblematic}
                className='btn btn-error btn-outline'>
                Remove User
            </button>

            <button
                onClick={handleClickBtnProblematic}
                className='btn btn-accent btn-outline'>
                Leave discussion
            </button>

            <button onClick={logMeOut} className='btn btn-primary'>
                Logout
            </button>
        </div>
    )
}

export default ChatMenuBtn
