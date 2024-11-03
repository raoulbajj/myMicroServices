import React, { useState, useEffect } from 'react'
import { Slide } from 'react-awesome-reveal';
import { RiSendPlaneFill } from "react-icons/ri";
import ChatMenuBtn from '../MainContent/ChatMenuBtn'
import Loader2 from '../Loaders/Loader2';
import Modale from '../Modale';

const HomePage = ({ setLoggedIn, loggedIn }) => {

  const [userDiscussions, setUserDiscussions] = useState([])
  const sessionId = sessionStorage.getItem('id')
  const [selectedDiscussion, setSelectedDiscussion] = useState(0)
  const [clickedDiscussion, setClickedDiscussion] = useState('No discussion selected')
  const [currentDiscussionId, setCurrentDiscussionId] = useState('')
  const [currentDiscussionMessages, setCurrentDiscussionMessages] = useState([])
  const [loading, setLoading] = useState(true)
  const [isModaleOpen, setIsModaleOpen] = useState(false)
  const [modaleProblematic, setModaleProblematic] = useState('Would you like to do something ?')

  const getAllMsgOfADiscussion = async (clickedDiscussion) => {
    const response = await fetch(`http://localhost:8888/discussion/getAllMsgOfADiscussion`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        title: clickedDiscussion
      })
    })
    const data = await response.json()
    setCurrentDiscussionMessages(data)
  }

  const handleDiscussionClick = (event) => {
    const currentText = event.currentTarget.textContent;
    setClickedDiscussion(currentText);
    setCurrentDiscussionId(event.currentTarget.id)
    getAllMsgOfADiscussion(currentText);
  };


  const fetchAllDiscussionsOfAUser = async (sessionId) => {
    const response = await fetch(`http://localhost:8888/discussion/getAllDiscussionsOfAUser/${sessionId}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })
    const data = await response.json()
    setUserDiscussions(data)
    console.log(userDiscussions)
    setLoading(false)
  }

  useEffect(() => {
    fetchAllDiscussionsOfAUser(sessionId)
  }, [])

  return (
    <Slide direction="down" duration={1000} className='h-full w-full'>


      {
        loading ?
          <Loader2 />

          :

          <div className='h-full w-full flex items-center justify-center bg-base-200 bg-opacity-70 rounded-xl border border-primary'>

            {/* DISCUSSION PART */}
            <div className='h-full bg-base-200 bg-opacity-40 w-[200px] flex flex-col items-center rounded-l-xl border-r border-primary'>
              <div className='w-full max-h-[97vh] rounded-tl-xl overflow-hidden'>

                {/* USER'S DISCUSSIONS MAPPING */}
                {userDiscussions.length > 0 ?
                  userDiscussions.map((discussion, key) => {
                    return (
                      <div
                        key={key}
                        id={discussion._id}
                        onClick={handleDiscussionClick}
                        className='text-center justify-center hover:text-sm active:bg-sky-600 hover:text-white w-full flex flex-wrap bg-base-200 p-3 text-primary cursor-pointer hover:bg-sky-800 transition-all duration-200 h-[10vh] border-b border-primary items-center'>
                        {discussion.title}
                      </div>
                    )
                  })

                  :

                  <div className='text-center justify-center hover:text-sm active:bg-sky-600 hover:text-white w-full flex flex-wrap bg-base-200 p-2 text-primary cursor-pointer hover:bg-sky-800 transition-all duration-200 h-[80px] border-b border-primary items-center'>
                    No discussion found
                  </div>
                }

              </div>
            </div>

            {/* CHATTING PART */}
            <div className='flex flex-col items-center justify-between text-2xl font-bold h-full w-full'>

              <div className='w-full p-3 border-b border-primary rounded-tr-xl flex justify-between h-[10vh] items-center flex-wrap'>
                <p className='text-primary tracking-tight text-5xl truncate max-w-[400px] VT323-font discussionTitleShadow'>
                  {clickedDiscussion}
                </p>

                <ChatMenuBtn setLoggedIn={setLoggedIn} loggedIn={loggedIn} modaleProblematic={modaleProblematic} setModaleProblematic={setModaleProblematic} setIsModaleOpen={setIsModaleOpen} />

              </div>

              {/* AFFICHAGE DES MESSAGES DES DISCUSSIONS */}
              {userDiscussions.length > 0 ?
                <div className='flex flex-col justify-start flex-grow max-h-[67vh] overflow-y-scroll w-full rounded-tr-xl bg-base-100 bg-opacity-60 '>
                  <p className='w-fit p-5 rounded-2xl text-primary flex flex-col gap-2'>
                    {
                      Array.isArray(currentDiscussionMessages) &&
                      currentDiscussionMessages.map((message) => {
                        return (
                          <p className='bg-base-300 w-fit p-5 rounded-2xl text-primary poppins-font font-normal'>
                            {message.message}
                          </p>
                        )
                      })
                    }
                  </p>
                </div>

                :

                <div className='flex flex-col justify-start flex-grow w-full rounded-tr-xl p-5 bg-base-100 bg-opacity-60'>
                  <p className='bg-base-300 w-fit p-5 rounded-2xl text-primary'>
                    No message found
                  </p>
                </div>
              }

              {/* SEND MESSAGE AREA */}
              <div className='w-full flex items-center justify-center bg-base-100 bg-opacity-0 rounded-br-xl border-primary border-t h-fit'>

                {/* TEXT AREA */}
                <input type='text' className='text-white min-h-[70px] w-full bg-transparent hover:bg-base-100 hover:bg-opacity-20 focus:bg-base-100 focus:bg-opacity-40 transition-all duration-200 p-2 px-3 border-r border-primary outline-none' placeholder='Type your message here...' />

                {/* SEND BTN */}
                <div className='flex items-center justify-center w-[80px] hover:bg-opacity-30 hover:bg-base-100 h-full rounded-br-xl'>
                  <RiSendPlaneFill className='logoColor h-full w-full cursor-pointer p-4 text-primary hover:scale-110 active:scale-75 transition-all duration-100' />
                </div>
              </div>

            </div>

            <Modale isModaleOpen={isModaleOpen} setIsModaleOpen={setIsModaleOpen} modaleProblematic={modaleProblematic} setModaleProblematic={setModaleProblematic} />
          </div>
      }
    </Slide>
  )
}

export default HomePage