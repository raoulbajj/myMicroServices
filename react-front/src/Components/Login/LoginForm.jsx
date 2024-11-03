import React, { useState, useEffect } from 'react'
import { useForm } from "react-hook-form"
import { MdAttachEmail } from "react-icons/md";
import { RiLockPasswordFill } from "react-icons/ri";
import { Link } from 'react-router-dom';
import { Slide, Flip } from 'react-awesome-reveal';


const LoginForm = ({ setLoggedIn, loggedIn, registerSelected, setRegisterSelected, loginSelected, setLoginSelected }) => {

    const { register, handleSubmit, formState: { errors } } = useForm()
    const [success, setSuccess] = useState(false)
    const [error, setError] = useState(false)
    const [loginResult, setLoginResult] = useState(false)
    const [emailError, setEmailError] = useState('input-bordered input-primary w-full input')
    const [passwordError, setPasswordError] = useState('input-bordered input-primary w-full input')

    const selectRegisterPage = () => {
        setRegisterSelected(true)
        setLoginSelected(false)
        setLoggedIn(false)
    }

    const onSubmit = async (data) => {
        const response = await fetch('http://localhost:8888/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: data.email,
                password: data.password
            })
        })
        const result = await response.json()

        if (result.token) {
            sessionStorage.setItem('token', result.token)
            sessionStorage.setItem('id', result.userid)
            sessionStorage.setItem('name', result.name)
            setSuccess(true)

            setTimeout(() => {
                setLoggedIn(true)
            }, 1000)
        }
        else {
            setLoginResult(false)
            setError('Wrong email or password !')
        }
    }

    useEffect(() => {
        if (errors.email) {
            setEmailError('input-bordered w-full input input-warning')
        }
        else if (errors.password) {
            setPasswordError('input-bordered w-full input input-warning')
        }
        else {
            setEmailError('input-bordered input-primary w-full input')
            setPasswordError('input-bordered input-primary w-full input')
        }
    }, [errors.email, errors.password])

    return (
        <Flip direction="horizontal" duration={800} className='w-full h-full flex flex-col items-center justify-center'>


            <form
                onSubmit={handleSubmit(onSubmit)}
                className='bg-base-300 p-5 rounded-xl flex flex-col items-center justify-center gap-5 w-full max-w-[600px] transition-all duration-300'>

                {/* TITLE */}
                <h1 className='text-5xl font-bold w-full text-center text-primary transition-all duration-200 title-animation'>LOGIN</h1>

                {/* AFFICHAGE DES ERREURS */}
                {
                    errors && errors.email ?
                        <p className='text-warning font-semibold text-center rounded-xl w-full'>{errors.email.message}</p>
                        :
                        errors && errors.password ?
                            <p className='text-warning font-semibold text-center rounded-xl w-full'>{errors.password.message}</p>
                            :
                            error ?
                                <p className='text-warning font-semibold text-center rounded-xl w-full'>{error}</p>
                                :
                                null
                }

                {/* EMAIL INPUT */}
                <div className='flex gap-2 w-full email-input items-center justify-center'>
                    <MdAttachEmail className='text-6xl text-primary xs:hidden' />
                    <input
                        {...register('email', {
                            required: 'Please complete the email field',
                            minLength: { value: 3, message: 'Your email adress is too short' }
                        })}
                        type="email"
                        className={emailError}
                        placeholder='Your E-mail'
                    />
                </div>

                {/* PASSWORD INPUT */}
                <div className='flex gap-2 w-full password-input items-center justify-center'>
                    <RiLockPasswordFill className='text-6xl text-primary xs:hidden locker' />
                    <input
                        {...register('password', {
                            required: 'Please complete the password field',
                            minLength: { value: 8, message: 'Your password is too short' }
                        })}
                        type="password"
                        className={passwordError}
                        placeholder='Your password'
                    />
                </div>

                {/* SUBMIT BUTTON */}
                {success ?
                    <div className='flex flex-col items-center justify-center gap-2 w-full'>
                        <p className='text-primary text-2xl font-semibold text-center rounded-xl w-full'>Loading...</p>
                        <span className="loader w-full text-accent"></span>
                    </div>
                    :
                    <button type='submit' className='btn btn-outline btn-primary w-full text-2xl'>Connect</button>
                }

                {/* REGISTER LINK */}
                {
                    success ?
                        false
                        :
                        <div className='w-full flex xss:flex-col gap-2 items-center justify-center'>
                            <p className='my-2 text-accent text-center'>Not registered yet ?</p>
                            <div onClick={selectRegisterPage} className='cursor-pointer font-semibold italic text-primary rounded-xl text-xl hover:scale-95 transition-all duration-200'>REGISTER</div>
                        </div>
                }
            </form >
        </Flip>
    )
}

export default LoginForm
