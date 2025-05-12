'use client';

import { Mail, RefreshCw } from 'lucide-react';
import { useState } from 'react';

export default function VerifyMailPage() {
    const [email, setEmail] = useState("");
    const [error, setError] = useState("");
    const [success, setSuccess] = useState("");
    const [isVerifying, setIsVerifying] = useState(false)
    
    const verifierMail = async (e) => {
        e.preventDefault();
        setIsVerifying(true);
        setError("");
        setSuccess("");
        try {
            const response = await fetch("http://127.0.0.1:8000/api/verify-mail", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json' 
                },
                body: JSON.stringify({ email})
            });
            const data = await response.json();
            if (!response.ok) {
                setError(data.message);
                return;
            }
            setSuccess("Un message a été envoyé a votre adresse email!");
        }   
        catch (error) {
        setError("Une erreur s'est produite lors de la vérification");
        } 
        finally {
            setIsVerifying(false); 
        }
    };
    
    return (
    <div className="flex items-center justify-center min-h-screen">
    <div className="bg-white rounded-3xl shadow-2xl p-8">
        <h2 className="p-15 mt-2 text-center">Verification de votre email</h2>
            <label className="block mb-3 text-sm font-medium text-gray-700/90" htmlFor="email">Adresse email</label>
            <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center  pointer-events-none transition-transform group-focus-within:-translate-y-1">
                    <Mail className="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" />
                </div>
                <input 
                    className="border pl-10 pr-4 py-3 border-gray-200 rounded-xl bg-white/90 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent shadow-sm transition-all duration-300 hover:border-gray-300 pl-10 p-2  w-full" 
                    placeholder="votre@email.com" 
                    type="email" 
                    id="email" 
                    name="email"
                    value = {email}
                    onChange={(e) => setEmail(e.target.value)}

                />
            </div>
            <div className="relative group">
                <button 
                    onClick={verifierMail}
                    className="mt-8 p-2 w-full border border-transparent shadow-lg rounded-xl text-lg font-semibold bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer" 
                    type="submit">
                    {isVerifying ? ( 
                    <>
                        <div className="absolute flex items-center inset-y-0 left-0 pl-3">
                            <RefreshCw className="animate-spin mr-2"/>
                        </div>
                        Verification en cours...
                    </>
                    ) : ( 
                        "Verifier"
                    )}
                </button>
                </div>
            {error && <p className="mt-4 text-red-500 text-center">{error}</p>}
            {success && <p className="mt-4 text-green-500 text-center">{success}</p>}    
    </div>
</div>
  )
}
