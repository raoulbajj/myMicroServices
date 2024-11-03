import React, { useState, useEffect } from 'react'
import { useForm } from "react-hook-form"
import { MdAttachEmail } from "react-icons/md";
import { RiLockPasswordFill } from "react-icons/ri";
import { Link, useNavigate } from 'react-router-dom';
import { FaIdCard } from "react-icons/fa6";
import { Flip, Fade } from 'react-awesome-reveal';
import { IoIosCheckbox } from "react-icons/io";

const RegisterForm = ({ setLoggedIn, loggedIn, registerSelected, setRegisterSelected, loginSelected, setLoginSelected }) => {

    const navigate = useNavigate()
    const { register, handleSubmit, formState: { errors } } = useForm()
    const [success, setSuccess] = useState(false)
    const [nameError, setNameError] = useState('input-bordered input-primary w-full input')
    const [emailError, setEmailError] = useState('input-bordered input-primary w-full input')
    const [passwordError, setPasswordError] = useState('input-bordered input-primary w-full input')

    const selectLoginPage = () => {
        setRegisterSelected(false)
        setLoginSelected(true)
        setLoggedIn(false)
    }

    const onSubmit = async (data) => {
        const response = await fetch('http://localhost:8888/user', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: data.name,
                email: data.email,
                password: data.password
            })
        })
        const registerResult = await response.json()

        if (registerResult) {
            setSuccess('User successfully registered !')

            setTimeout(() => {
                setRegisterSelected(false)
                setLoginSelected(true)
                setLoggedIn(false)
            }, 1000)
        }
    }

    useEffect(() => {
        if (errors.name) {
            setNameError('input-bordered w-full input input-warning')
        }
        else if (errors.email) {
            setEmailError('input-bordered w-full input input-warning')
        }
        else if (errors.password) {
            setPasswordError('input-bordered w-full input input-warning')
        }
        else {
            setNameError('input-bordered input-primary w-full input')
            setEmailError('input-bordered input-primary w-full input')
            setPasswordError('input-bordered input-primary w-full input')
        }
    }, [errors.email, errors.password])

    return (
        <Flip direction="horizontal" duration={800} className='w-full flex-grow flex items-center justify-center'>

            <form
                onSubmit={handleSubmit(onSubmit)}
                className='bg-base-300 p-5 rounded-xl flex flex-col items-center justify-center gap-5 w-full max-w-[600px] transition-all duration-300'>

                {/* TITLE */}
                <h1 className='text-5xl title-animation italic font-bold w-full text-center text-primary xss:text-4xl'>REGISTER</h1>

                {/* AFFICHAGE DES ERREURS */}
                {errors && errors.name ?
                    <p className='text-warning font-semibold text-center rounded-xl w-full'> {errors.name.message}</p>
                    :
                    errors && errors.email ?
                        <p className='text-warning font-semibold text-center rounded-xl w-full'> {errors.email.message}</p>
                        :
                        errors && errors.password ?
                            <p className='text-warning font-semibold text-center rounded-xl w-full'> {errors.password.message}</p>
                            :
                            false
                }

                {/* NAME INPUT */}
                <div className='flex gap-2 items-center justify-center name-input w-full'>
                    <FaIdCard className='text-6xl text-primary xs:hidden' />
                    <input
                        {...register('name', {
                            required: 'Please complete the name field',
                            minLength: { value: 3, message: 'Your name is too short' }
                        })}
                        type="text"
                        className={nameError}
                        placeholder='Your Full Name'
                    />
                </div>

                {/* EMAIL INPUT */}
                <div className='flex gap-2 w-full email-input items-center justify-center'>
                    <MdAttachEmail className='text-6xl text-primary xs:hidden lockerEmailRegister' />
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
                    <RiLockPasswordFill className='text-6xl text-primary xs:hidden lockerRegister' />
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

                {/* SUCCESS MESSAGE */}
                {success &&
                    <div className='w-full flex gap-2 items-center justify-center'>
                        <div className='text-black font-semibold text-center rounded-xl w-full flex items-center justify-center gap-2'>
                            <p className='text-primary font-semibold text-center rounded'>
                                {success}
                            </p>

                            <IoIosCheckbox className='text-5xl text-accent' />
                        </div>
                    </div>

                }

                {/* SUBMIT BUTTON */}
                {success ?
                    <span className="loader w-full text-accent"></span>
                    :
                    <button type='submit' className='btn btn-outline btn-primary w-full text-2xl'>Validate</button>
                }

                {success ?
                    false
                    :
                    <div className='w-full flex xss:flex-col gap-2 items-center justify-center'>
                        <p className='my-2 text-accent text-center'>Already registered ?</p>
                        <div onClick={selectLoginPage} className='cursor-pointer font-semibold italic text-primary rounded-xl text-xl hover:scale-95 transition-all duration-200'>LOGIN</div>
                    </div>
                }
            </form >
        </Flip>

    )
}

export default RegisterForm
