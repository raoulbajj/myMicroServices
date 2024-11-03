import React from "react"

const SwitchLogBtn = ({ setLoggedIn, loggedIn }) => {

    const switchLoggedInTest = () => {
        setLoggedIn(!loggedIn)
    }


    return (
        loggedIn == false ?
            <button onClick={switchLoggedInTest} className='btn btn-primary m-10'>
                Log In manually
            </button>
            :
            <button onClick={switchLoggedInTest} className='btn btn-accent m-10'>
                Log Out manually
            </button>
    )
}

export default SwitchLogBtn
