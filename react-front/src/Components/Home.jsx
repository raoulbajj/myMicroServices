import React, { useState, useEffect } from 'react'
import LoginForm from './Login/LoginForm'
import MainContent from './MainContent/MainContent'
import { Fade } from 'react-awesome-reveal'
import RegisterForm from './Register/RegisterForm'

const Home = () => {

    const [token, setToken] = useState(sessionStorage.getItem('token'))
    const [loggedIn, setLoggedIn] = useState(token ? true : false);
    const [loginSelected, setLoginSelected] = useState(true)
    const [registerSelected, setRegisterSelected] = useState(false)

    const fetchToken = async (myToken) => {
        const response = await fetch('http://localhost:8888/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ token: myToken }),
        })
        const data = await response.json()
        if (!data.error) {
            setLoggedIn(true)
        }
        else {
            setLoggedIn(false)
        }
    }

    useEffect(() => {
        if (loggedIn == false) {
            sessionStorage.clear()
        }

        addEventListener('storage', () => {
            setToken(sessionStorage.getItem('token'))
            fetchToken(token)
        })
    }, [loggedIn])

    return (
        <div className='h-full w-full flex flex-col items-center justify-center'>
            {!loggedIn && loginSelected && !registerSelected ? (
                <LoginForm
                    setLoggedIn={setLoggedIn}
                    loggedIn={loggedIn}
                    loginSelected={loginSelected}
                    setLoginSelected={setLoginSelected}
                    registerSelected={registerSelected}
                    setRegisterSelected={setRegisterSelected}
                />
            ) : !loggedIn && !loginSelected && registerSelected ? (
                <RegisterForm
                    setLoggedIn={setLoggedIn}
                    loggedIn={loggedIn}
                    loginSelected={loginSelected}
                    setLoginSelected={setLoginSelected}
                    registerSelected={registerSelected}
                    setRegisterSelected={setRegisterSelected}
                />
            ) : (
                <MainContent
                    setLoggedIn={setLoggedIn}
                    loggedIn={loggedIn}
                    loginSelected={loginSelected}
                    setLoginSelected={setLoginSelected}
                    registerSelected={registerSelected}
                    setRegisterSelected={setRegisterSelected}
                />
            )}


            <Fade className='text-2xl p-2 italic bg-base-300 mt-5 title-animation raoul text-center'>
                MADE BY RAOUL BAJJANI &copy; 2024
            </Fade>
        </div>
    )
}

export default Home
