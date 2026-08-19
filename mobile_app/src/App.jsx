import React, { useState, useEffect } from 'react';
import Header from './components/Header';
import Navigation from './components/Navigation';
import StoryTray from './components/StoryTray';
import StoryViewerModal from './components/StoryViewerModal';
import PostComposer from './components/PostComposer';
import PostCard from './components/PostCard';
import ReelsTab from './components/ReelsTab';
import MessengerTab from './components/MessengerTab';
import NotificationsTab from './components/NotificationsTab';
import ProfileMenuTab from './components/ProfileMenuTab';
import SearchModal from './components/SearchModal';

import {
  initialCurrentUser,
  initialStories,
  initialPosts,
  initialReels,
  initialChats,
  initialNotifications
} from './mockData';

export default function App() {
  const [currentUser, setCurrentUser] = useState(initialCurrentUser);
  const [activeTab, setActiveTab] = useState('feed');
  const [isDarkMode, setIsDarkMode] = useState(true);

  // Data States
  const [stories, setStories] = useState(() => {
    const saved = localStorage.getItem('facepost_stories');
    return saved ? JSON.parse(saved) : initialStories;
  });

  const [posts, setPosts] = useState(() => {
    const saved = localStorage.getItem('facepost_posts');
    return saved ? JSON.parse(saved) : initialPosts;
  });

  const [reels, setReels] = useState(() => {
    const saved = localStorage.getItem('facepost_reels');
    return saved ? JSON.parse(saved) : initialReels;
  });

  const [chats, setChats] = useState(() => {
    const saved = localStorage.getItem('facepost_chats');
    return saved ? JSON.parse(saved) : initialChats;
  });

  const [notifications, setNotifications] = useState(() => {
    const saved = localStorage.getItem('facepost_notifs');
    return saved ? JSON.parse(saved) : initialNotifications;
  });

  // Modals
  const [isCreatePostOpen, setIsCreatePostOpen] = useState(false);
  const [isSearchOpen, setIsSearchOpen] = useState(false);
  const [selectedStoryIndex, setSelectedStoryIndex] = useState(null);
  const [activeChatId, setActiveChatId] = useState(null);

  // Sync to LocalStorage
  useEffect(() => {
    localStorage.setItem('facepost_posts', JSON.stringify(posts));
  }, [posts]);

  useEffect(() => {
    localStorage.setItem('facepost_stories', JSON.stringify(stories));
  }, [stories]);

  useEffect(() => {
    localStorage.setItem('facepost_chats', JSON.stringify(chats));
  }, [chats]);

  useEffect(() => {
    localStorage.setItem('facepost_notifs', JSON.stringify(notifications));
  }, [notifications]);

  // Handle Dark Mode
  useEffect(() => {
    if (isDarkMode) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }, [isDarkMode]);

  // Counts
  const unreadNotifCount = notifications.filter(n => n.unread).length;
  const unreadMsgCount = chats.reduce((acc, c) => acc + (c.unreadCount || 0), 0);

  // Handlers
  const handleToggleTheme = () => {
    setIsDarkMode(!isDarkMode);
  };

  const handleAddNewPost = (newPost) => {
    setPosts([newPost, ...posts]);
  };

  const handleReactPost = (postId, reactionType) => {
    setPosts(prevPosts =>
      prevPosts.map(post => {
        if (post.id === postId) {
          const oldReaction = post.userReaction;
          const newReactions = { ...post.reactions };

          if (oldReaction && newReactions[oldReaction] > 0) {
            newReactions[oldReaction] -= 1;
          }

          if (reactionType) {
            newReactions[reactionType] = (newReactions[reactionType] || 0) + 1;
          }

          return {
            ...post,
            userReaction: reactionType,
            reactions: newReactions
          };
        }
        return post;
      })
    );
  };

  const handleAddComment = (postId, newComment) => {
    setPosts(prevPosts =>
      prevPosts.map(post => {
        if (post.id === postId) {
          return {
            ...post,
            comments: [...(post.comments || []), newComment],
            commentsCount: (post.commentsCount || 0) + 1
          };
        }
        return post;
      })
    );
  };

  const handleLikeReel = (reelId) => {
    setReels(prevReels =>
      prevReels.map(reel => {
        if (reel.id === reelId) {
          return {
            ...reel,
            isLiked: !reel.isLiked,
            likes: reel.isLiked ? '42.8K' : '42.9K'
          };
        }
        return reel;
      })
    );
  };

  const handleSendMessage = (chatId, message) => {
    setChats(prevChats =>
      prevChats.map(chat => {
        if (chat.id === chatId) {
          return {
            ...chat,
            messages: [...chat.messages, message],
            lastMessage: {
              text: message.text,
              sender: message.sender,
              timestamp: message.time
            }
          };
        }
        return chat;
      })
    );
  };

  const handleSelectChat = (chatId) => {
    setActiveChatId(chatId);
    // Mark as read
    setChats(prev => prev.map(c => c.id === chatId ? { ...c, unreadCount: 0 } : c));
  };

  const handleMarkAllNotifsRead = () => {
    setNotifications(prev => prev.map(n => ({ ...n, unread: false })));
  };

  const handleAcceptFriendRequest = (notifId) => {
    setNotifications(prev => prev.filter(n => n.id !== notifId));
    alert('Friend request confirmed! Added to your friend list. 🤝🎉');
  };

  const handleAddStory = () => {
    const newStory = {
      id: `story_${Date.now()}`,
      user: {
        id: currentUser.id,
        name: currentUser.name,
        avatar: currentUser.avatar,
        isOnline: true
      },
      mediaUrl: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=80",
      timeAgo: "Just now",
      caption: "Added a new story update! ✨🔥",
      unread: true
    };
    setStories([newStory, ...stories]);
    alert('Story posted successfully! 📸✨');
  };

  const activeChat = chats.find(c => c.id === activeChatId);

  return (
    <div className="app-container">
      {/* Top Header */}
      <Header
        onOpenCreatePost={() => setIsCreatePostOpen(true)}
        onOpenSearch={() => setIsSearchOpen(true)}
        onNavigateToMessages={() => setActiveTab('messages')}
        unreadMessagesCount={unreadMsgCount}
        isDarkMode={isDarkMode}
        onToggleTheme={handleToggleTheme}
      />

      {/* Main Top / Secondary Navigation Tab Bar */}
      <Navigation
        activeTab={activeTab}
        setActiveTab={setActiveTab}
        unreadNotifCount={unreadNotifCount}
        unreadMsgCount={unreadMsgCount}
        currentUser={currentUser}
      />

      {/* Dynamic Viewport */}
      <main className="main-viewport">
        {activeTab === 'feed' && (
          <>
            {/* Post Composer Card */}
            <PostComposer
              currentUser={currentUser}
              onAddNewPost={handleAddNewPost}
              isOpenModal={isCreatePostOpen}
              onOpenModal={() => setIsCreatePostOpen(true)}
              onCloseModal={() => setIsCreatePostOpen(false)}
            />

            {/* Story Tray */}
            <StoryTray
              stories={stories}
              currentUser={currentUser}
              onSelectStory={(index) => setSelectedStoryIndex(index)}
              onAddStory={handleAddStory}
            />

            {/* Newsfeed Posts */}
            <div style={{ display: 'flex', flexDirection: 'column' }}>
              {posts.map(post => (
                <PostCard
                  key={post.id}
                  post={post}
                  currentUser={currentUser}
                  onReact={handleReactPost}
                  onAddComment={handleAddComment}
                />
              ))}
            </div>
          </>
        )}

        {activeTab === 'reels' && (
          <ReelsTab
            reels={reels}
            onLikeReel={handleLikeReel}
          />
        )}

        {activeTab === 'messages' && (
          <MessengerTab
            chats={chats}
            onSelectChat={handleSelectChat}
            onSendMessage={handleSendMessage}
            activeChat={activeChat}
            onCloseActiveChat={() => setActiveChatId(null)}
          />
        )}

        {activeTab === 'notifications' && (
          <NotificationsTab
            notifications={notifications}
            onMarkAllAsRead={handleMarkAllNotifsRead}
            onAcceptFriendRequest={handleAcceptFriendRequest}
          />
        )}

        {activeTab === 'profile' && (
          <ProfileMenuTab
            currentUser={currentUser}
            isDarkMode={isDarkMode}
            onToggleTheme={handleToggleTheme}
            onAddStory={handleAddStory}
          />
        )}
      </main>

      {/* Story Fullscreen Viewer Modal */}
      {selectedStoryIndex !== null && (
        <StoryViewerModal
          stories={stories}
          initialStoryIndex={selectedStoryIndex}
          onClose={() => setSelectedStoryIndex(null)}
          onStoryReact={(storyId) => {
            console.log('Story reacted:', storyId);
          }}
        />
      )}

      {/* Search Modal */}
      {isSearchOpen && (
        <SearchModal
          onClose={() => setIsSearchOpen(false)}
        />
      )}
    </div>
  );
}
