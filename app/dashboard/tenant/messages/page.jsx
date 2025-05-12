'use client';
import { Paperclip, Send, Search, MoreVertical, ChevronLeft, Smile, Mic, MessageSquare } from 'lucide-react';
import { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import Avatar from '@/components/ui/avatar';
import '@/hooks/echo';

export default function TenantMessagesPage() {
  const [conversations, setConversations] = useState([]);
  const [activeConversation, setActiveConversation] = useState(null);
  const [messages, setMessages] = useState([]);
  const [newMessage, setNewMessage] = useState("");
  const [searchTerm, setSearchTerm] = useState("");
  const [isMobileView, setIsMobileView] = useState(false);
  const messagesEndRef = useRef(null);
  const [token, setToken] = useState(null);
  const [userId, setUserId] = useState(null);
  const filteredConversations = conversations.filter(conv =>
    conv.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    conv.lastMessage.toLowerCase().includes(searchTerm.toLowerCase())
  );

  useEffect(() => {
    setToken(sessionStorage.getItem('auth_token'));
    setUserId(sessionStorage.getItem('user_id'));
  }, []);

  useEffect(() => {
    const fetchAgents = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/utilisateurs/agent_immobilier', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept' : 'application/json'
          }
        });
        
        const data = await response.json();
        // Transformez les agents en conversations
        const agentsAsConversations = data.map(agent => ({
          id: agent.idUser,
          name: `${agent.prenom} ${agent.nom}`,
          lastMessage: "Contactez-moi pour vos besoins immobiliers", // Message par défaut
          time: "", // À adapter si vous avez la date du dernier message
          unread: 0, // À adapter si vous suivez les messages non lus
          avatar: "/avatars/agent.jpg", // Photo de profil ou image par défaut
          online: false // Statut de connexion (à adapter si disponible)
        }));
        
        setConversations(agentsAsConversations);
        
      } catch (error) {
        console.error("Erreur chargement agents:", error);
        // En cas d'erreur, conservez les conversations vides
      }
    };
  
    if (token) {
      fetchAgents();
    }
  }, [token]);
  

  useEffect(() => {
    const fetchMessages = async () => {
      if (!activeConversation) return;
  
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/chat`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json',
              'Accept' : 'application/json'
            },
            body: JSON.stringify({
              conversation_id: activeConversation,  // Le `conversation_id` est l'ID de la conversation active
            }),
          }
        );
  
        if (!response.ok) {
          throw new Error('Erreur lors de la récupération des messages');
        }
  
        const messages = await response.json();
        // Transformation des messages API vers le format de votre UI
        const formattedMessages = messages.map(message => ({
          id: message.id,
          text: message.message,
          sender: message.from_id == userId ? 'tenant' : 'owner',
          time: new Date(message.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          date: new Date(message.createdAt).toLocaleDateString('fr-FR', { day: 'numeric', month: 'numeric', year: 'numeric' }),
          read: 'read',
        })); 
  
        setMessages(formattedMessages);
        
        // Mise à jour des conversations (marquer comme lus)
        setConversations(conv => conv.map(c => 
          c.id === activeConversation ? {...c, unread: 0} : c
        ));
  
      } catch (error) {
        console.error("Erreur:", error);
        // Optionnel: Afficher un message à l'utilisateur
      }
    };
  
    fetchMessages();
  }, [activeConversation, token]);

  useEffect(() => {
    const handleResize = () => {
      setIsMobileView(window.innerWidth < 768);
    };
    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  const handleSendMessage = async (e) => {
    const receiverId = activeConversation;
    if (newMessage.trim() === "") return;
    
    try {

      const response = await fetch('http://127.0.0.1:8000/api/message', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization' : `Bearer ${token}`, // Ajout du token pour l'authentification
          'Accept' : 'application/json'
        },
        body: JSON.stringify({
          user_id: receiverId,  // Identifiant du destinataire
          from: userId,  // Identifiant de l'utilisateur actuel (expéditeur)
          message: newMessage,  // Message envoyé
          conversation_id: receiverId,
        }),
      });
      if (!response.ok) {
        throw new Error('Erreur lors de l\'envoi du message');
      }

      const data = await response.json();
      const newMsg = {
        id: data.id,  
        text: newMessage,
        sender: data.sender == userId ? 'tenant' : 'owner',
        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        date: data.createdAt,  // Date du message (peut être ajustée si nécessaire)
      };

    setMessages([...messages, newMsg]);
    setNewMessage("");  // Réinitialisation de l'input

    } catch (error) {
      console.error("Erreur d'envoi :", error); // Gestion des erreurs
    }
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      handleSendMessage();
    }
  };

useEffect(() => {
  const minId = Math.min(userId, activeConversation);
  const maxId = Math.max(userId, activeConversation);
  if (!userId) {
    console.log('userId not loaded yet:', userId);
    return;
  }

   if (!activeConversation) {
    console.log('userId not loaded yet:', userId);
    return;
  }

  if (!window.Echo) {
    console.error('Echo not initialized');
    return;
  }

  console.log(`✅ Subscribing to chat.${userId}`);

  const channel = window.Echo.private(`chat.${minId}.${maxId}`);
  
  channel.listen('.MessageSent', (data) => {
    console.log('📩 Message received:', data);
     if (data.sender.id == activeConversation) {
    setMessages(prev => [...prev, {
      id: data.id,
      text: data.message,
      sender: data.sender.id === userId ? 'tenant' : 'owner',
      time: new Date(data.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
      date: new Date(data.createdAt).toLocaleDateString('fr-FR')
    }]);
    }
  });

  return () => {
    console.log('🧹 Unsubscribing from channel');
    channel.stopListening('.MessageSent');
    window.Echo.leave(`chat.${userId}`);
  };
}, [userId, activeConversation]); // Se ré-exécute uniquement quand userId change // On retire echo des dépendances

  return (
    <div className="flex h-[calc(100vh-64px)] bg-white">
      <div className={`bg-gray-50 border-r border-gray-200 w-full md:w-96 flex-shrink-0 ${isMobileView && activeConversation ? 'hidden' : 'block'}`}>
        <div className="p-4 border-b border-gray-200 bg-white">
          <div className="flex justify-between items-center mb-2">
            <h1 className="text-xl font-bold text-gray-800">Messages</h1>
            <button className="p-1 rounded-full hover:bg-gray-100">
              <MoreVertical className="text-gray-500" size={20} />
            </button>
          </div>
          
          <div className="relative">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search className="text-gray-400" size={18} />
            </div>
            <input
              type="text"
              className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
              placeholder="Rechercher une conversation..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>
        </div>

        <div className="overflow-y-auto h-[calc(100%-80px)]">
          {filteredConversations.length > 0 ? (
            filteredConversations.map((conv) => (
              <div
                key={conv.id}
                className={`flex items-center p-3 border-b border-gray-100 cursor-pointer hover:bg-gray-100 transition-colors ${activeConversation === conv.id ? 'bg-blue-50' : 'bg-white'}`}
                onClick={() => setActiveConversation(conv.id)}
              >
                <div className="relative mr-3">
                  <Avatar src={conv.avatar} alt={conv.name} />
                  {conv.online && (
                    <div className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                  )}
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex justify-between items-center">
                    <h3 className="text-sm font-medium text-gray-900 truncate">{conv.name}</h3>
                    <span className="text-xs text-gray-500 whitespace-nowrap ml-2">{conv.time}</span>
                  </div>
                  <div className="flex justify-between items-center">
                    <p className="text-sm text-gray-500 truncate">{conv.lastMessage}</p>
                    {conv.unread > 0 && (
                      <span className="bg-blue-500 text-white text-xs font-medium rounded-full h-5 w-5 flex items-center justify-center ml-2">
                        {conv.unread}
                      </span>
                    )}
                  </div>
                </div>
              </div>
            ))
          ) : (
            <div className="p-4 text-center text-gray-500">
              Aucune conversation trouvée
            </div>
          )}
        </div>
      </div>

      {activeConversation ? (
        <div className={`flex flex-col flex-1 ${isMobileView && !activeConversation ? 'hidden' : 'flex'}`}>
          <div className="bg-white p-3 border-b border-gray-200 flex items-center">
            {isMobileView && (
              <button 
                onClick={() => setActiveConversation(null)}
                className="mr-2 p-1 rounded-full hover:bg-gray-100"
              >
                <ChevronLeft size={20} />
              </button>
            )}
            <div className="relative mr-3">
              <Avatar 
                src={conversations.find(c => c.id === activeConversation)?.avatar} 
                alt={conversations.find(c => c.id === activeConversation)?.name} 
              />
              {conversations.find(c => c.id === activeConversation)?.online && (
                <div className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
              )}
            </div>
            <div className="flex-1">
              <h2 className="font-medium text-gray-900">
                {conversations.find(c => c.id === activeConversation)?.name}
              </h2>
              <p className="text-xs text-gray-500">
                {conversations.find(c => c.id === activeConversation)?.online ? "En ligne" : "Hors ligne"}
              </p>
            </div>
            <button className="p-1 rounded-full hover:bg-gray-100">
              <MoreVertical className="text-gray-500" size={20} />
            </button>
          </div>

          <div className="flex-1 p-4 overflow-y-auto bg-gray-50">
            <div className="space-y-4">
              {messages.map((message, index) => {
                const showDate = index === 0 || messages[index-1].date !== message.date;
                return (
                  <div key={`message-${message.id}-${index}`}> 
                    {showDate && (
                      <div key={`date-${message.id}`} className="flex justify-center">
                        <span className="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">
                          {message.date}
                        </span>
                      </div>
                    )}
                    <div
                      key={message.id}
                      className={`flex ${message.sender === 'tenant' ? 'justify-end' : 'justify-start'}`}
                    >
                      <div
                        className={`max-w-xs md:max-w-md rounded-lg px-4 py-2 ${message.sender === 'tenant' 
                          ? 'bg-blue-500 text-white rounded-br-none' 
                          : 'bg-white border border-gray-200 rounded-bl-none'}`}
                      >
                        <p className="text-sm">{message.text}</p>
                        <div className={`flex items-center justify-end mt-1 space-x-1 ${message.sender === 'tenant' ? 'text-blue-100' : 'text-gray-400'}`}>
                          <span className="text-xs">{message.time}</span>
                          {message.sender === 'tenant' && (
                            message.read ? (
                              <span className="text-xs">✓✓</span>
                            ) : (
                              <span className="text-xs">✓</span>
                            )
                          )}
                        </div>
                      </div>
                    </div>
                    </div>
                );
              })}
              <div ref={messagesEndRef} />
            </div>
          </div>

          <div className="bg-white p-3 border-t border-gray-200">
            <div className="flex items-center space-x-2">
              <button className="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                <Smile size={20} />
              </button>
              <button className="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                <Paperclip size={20} />
              </button>
              <div className="flex-1">
                <input
                  type="text"
                  name = "message"
                  className="block w-full rounded-full border border-gray-300 bg-gray-50 py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                  placeholder="Écrivez un message..."
                  value={newMessage}
                  onChange={(e) => setNewMessage(e.target.value)}
                  onKeyPress={handleKeyPress}
                />
              </div>
              <button
                className={`p-2 rounded-full ${newMessage.trim() ? 'bg-blue-500 text-white hover:bg-blue-600' : 'text-gray-400 hover:bg-gray-100'}`}
                onClick={handleSendMessage}
              >
                {newMessage.trim() ? <Send size={20} /> : <Mic size={20} />}
              </button>
            </div>
          </div>
        </div>
      ) : (
        !isMobileView && (
          <div className="hidden md:flex flex-1 items-center justify-center bg-gray-50">
            <div className="text-center p-6 max-w-md">
              <div className="mx-auto h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                <MessageSquare className="text-gray-500" size={24} />
              </div>
              <h3 className="text-lg font-medium text-gray-900 mb-1">Aucune conversation sélectionnée</h3>
              <p className="text-gray-500 text-sm">
                Sélectionnez une conversation dans la liste ou démarrez une nouvelle discussion.
              </p>
            </div>
          </div>
        )
      )}
    </div>
  );
}