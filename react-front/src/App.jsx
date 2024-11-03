import React, { useState } from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Home from './Components/Home';
import NotFound from './Components/NotFound';
import Register from './Components/Register/RegisterForm';
function App() {
  const [isLoading, setIsLoading] = useState(false);

  const startLoading = () => {
    setIsLoading(true);
  };

  const stopLoading = () => {
    setIsLoading(false);
  };

  return (
    <main className='flex flex-col flex-grow h-full w-full overflow-hidden'>

      {isLoading ?
        <div className='items-center justify-center w-full flex-grow flex flex-col'>
          <div className="loader"></div>
        </div>

        :

        <Router>
          <Routes>

            {/* ROUTE HOME */}
            <Route
              path='/'
              element={<Home />}
              onStart={startLoading}
              onEnd={stopLoading}
            />

            {/* ROUTE REGISTER */}
            <Route
              path='/register'
              element={<Register />}
              onStart={startLoading}
              onEnd={stopLoading}
            />

            {/* ROUTE 404 NOT FOUND */}
            <Route
              path="*"
              element={<NotFound />}
            />

          </Routes>
        </Router>
      }
    </main>
  );
}

export default App;
