export const initialCurrentUser = {
  id: "user_me",
  name: "Mahidul Shanto",
  username: "mahidul.shanto",
  avatar: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80",
  coverPhoto: "https://images.unsplash.com/photo-1707343843437-caacff5cfa74?w=800&auto=format&fit=crop&q=80",
  bio: "🌟 Digital Creator | Passionate Developer | Living life with code and adventures ✨",
  work: "Full-Stack Developer at TheFacePost",
  education: "Studied Computer Science",
  livesIn: "Dhaka, Bangladesh",
  from: "Rajshahi, Bangladesh",
  followersCount: "12.4K",
  friendsCount: "1,248",
  followingCount: "420",
  verified: true
};

export const initialStories = [
  {
    id: "story_1",
    user: {
      id: "u_1",
      name: "Tanjila Akter",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80",
      isOnline: true
    },
    mediaUrl: "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80",
    timeAgo: "2h",
    caption: "Beautiful sunset evening 🌅✨",
    unread: true
  },
  {
    id: "story_2",
    user: {
      id: "u_2",
      name: "Rakibul Islam",
      avatar: "https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80",
      isOnline: true
    },
    mediaUrl: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&auto=format&fit=crop&q=80",
    timeAgo: "4h",
    caption: "Weekend beach trip with boys 🏖️🌊",
    unread: true
  },
  {
    id: "story_3",
    user: {
      id: "u_3",
      name: "Nusrat Jahan",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80",
      isOnline: false
    },
    mediaUrl: "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&auto=format&fit=crop&q=80",
    timeAgo: "6h",
    caption: "Coffee & Code vibes ☕💻",
    unread: true
  },
  {
    id: "story_4",
    user: {
      id: "u_4",
      name: "Farhan Ahmed",
      avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80",
      isOnline: true
    },
    mediaUrl: "https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=600&auto=format&fit=crop&q=80",
    timeAgo: "8h",
    caption: "Night rooftop music jam 🎸🎵",
    unread: false
  },
  {
    id: "story_5",
    user: {
      id: "u_5",
      name: "Samira Khan",
      avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80",
      isOnline: true
    },
    mediaUrl: "https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=600&auto=format&fit=crop&q=80",
    timeAgo: "12h",
    caption: "City lights exploring 🌃",
    unread: false
  }
];

export const initialPosts = [
  {
    id: "post_1",
    author: {
      id: "u_1",
      name: "Tanjila Akter",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80",
      verified: true,
      badge: "Top Creator"
    },
    timeAgo: "35m",
    privacy: "public",
    content: "Just launched our brand new mobile app experience! Loving the smooth UI, fast response, and clean design. What do you guys think? 🚀✨ #tech #innovation #thefacepost",
    media: [
      "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=80"
    ],
    reactions: {
      like: 48,
      love: 32,
      care: 12,
      haha: 2,
      wow: 8,
      sad: 0,
      angry: 0
    },
    userReaction: "love",
    commentsCount: 19,
    sharesCount: 7,
    comments: [
      {
        id: "c_1",
        author: {
          name: "Rakibul Islam",
          avatar: "https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80"
        },
        text: "The new UI is super slick! Congrats 🎉",
        timeAgo: "20m",
        likes: 5
      },
      {
        id: "c_2",
        author: {
          name: "Nusrat Jahan",
          avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80"
        },
        text: "Loving the reels and instant messenger features ❤️",
        timeAgo: "12m",
        likes: 3
      }
    ]
  },
  {
    id: "post_2",
    author: {
      id: "u_2",
      name: "Rakibul Islam",
      avatar: "https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80",
      verified: false
    },
    timeAgo: "2h",
    privacy: "friends",
    content: "Nothing beats a peaceful afternoon by the serene river with great friends and good vibes 🌿🛶",
    media: [
      "https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&auto=format&fit=crop&q=80"
    ],
    reactions: {
      like: 85,
      love: 44,
      care: 9,
      haha: 1,
      wow: 15,
      sad: 0,
      angry: 0
    },
    userReaction: null,
    commentsCount: 24,
    sharesCount: 3,
    comments: [
      {
        id: "c_3",
        author: {
          name: "Farhan Ahmed",
          avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80"
        },
        text: "Next time I am coming with you guys! 📸",
        timeAgo: "1h",
        likes: 2
      }
    ]
  },
  {
    id: "post_3",
    author: {
      id: "u_4",
      name: "The FacePost Official",
      avatar: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=150&auto=format&fit=crop&q=80",
      verified: true,
      badge: "Official"
    },
    timeAgo: "5h",
    privacy: "public",
    content: "📢 Welcome to The FacePost v1.0 Mobile! Explore full-screen Reels, end-to-end instant messaging, rich status updates, and customized dark mode. Stay connected with friends anywhere, anytime! 💙🔥",
    media: [],
    reactions: {
      like: 210,
      love: 156,
      care: 45,
      haha: 4,
      wow: 22,
      sad: 0,
      angry: 0
    },
    userReaction: "like",
    commentsCount: 52,
    sharesCount: 38,
    comments: []
  }
];

export const initialReels = [
  {
    id: "reel_1",
    creator: {
      id: "u_1",
      name: "Tanjila Akter",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80",
      isFollowing: false
    },
    description: "Catching the golden hour glow ✨🌅 Which view do you like best? #nature #sunset #goldenhour",
    music: "Original Sound - Tanjila • Aesthetic Vibes",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-tree-branches-in-the-breeze-1188-large.mp4",
    posterUrl: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&auto=format&fit=crop&q=80",
    likes: "42.8K",
    isLiked: true,
    comments: "1.2K",
    shares: "890"
  },
  {
    id: "reel_2",
    creator: {
      id: "u_2",
      name: "Rakibul Islam",
      avatar: "https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80",
      isFollowing: true
    },
    description: "Coding our new app interface at 2 AM with infinite coffee ☕💻 #developer #coding #uiux",
    music: "Lofi Coding Beats • Chill Hop",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-hands-holding-smartphone-scrolling-42544-large.mp4",
    posterUrl: "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80",
    likes: "18.5K",
    isLiked: false,
    comments: "430",
    shares: "215"
  },
  {
    id: "reel_3",
    creator: {
      id: "u_5",
      name: "Samira Khan",
      avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80",
      isFollowing: false
    },
    description: "Quick street food tour in Old Dhaka! The flavors are unreal 😋🌶️🍲 #foodie #dhaka #streetfood",
    music: "Foodie Anthem • Street Beats",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-serving-dinner-to-friends-42543-large.mp4",
    posterUrl: "https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=600&auto=format&fit=crop&q=80",
    likes: "67.1K",
    isLiked: false,
    comments: "3.4K",
    shares: "2.8K"
  }
];

export const initialChats = [
  {
    id: "chat_1",
    user: {
      id: "u_1",
      name: "Tanjila Akter",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80",
      isOnline: true,
      lastSeen: "Active now"
    },
    unreadCount: 3,
    lastMessage: {
      text: "Did you check out the new update?",
      sender: "them",
      timestamp: "12:24 PM"
    },
    messages: [
      { id: "m1", sender: "them", text: "Hey Mahidul! How is the project going?", time: "12:15 PM" },
      { id: "m2", sender: "me", text: "Going amazing! Just finishing the new UI and APK build 🚀", time: "12:18 PM" },
      { id: "m3", sender: "them", text: "That sounds awesome! Send me the APK as soon as it's ready!", time: "12:20 PM" },
      { id: "m4", sender: "them", text: "Did you check out the new update?", time: "12:24 PM" }
    ]
  },
  {
    id: "chat_2",
    user: {
      id: "u_2",
      name: "Rakibul Islam",
      avatar: "https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80",
      isOnline: true,
      lastSeen: "Active 5m ago"
    },
    unreadCount: 2,
    lastMessage: {
      text: "See you in the evening meetup! ☕",
      sender: "them",
      timestamp: "11:45 AM"
    },
    messages: [
      { id: "m21", sender: "me", text: "Are we meeting today for coffee?", time: "11:30 AM" },
      { id: "m22", sender: "them", text: "Yes! Dhanmondi Lake around 5:30 PM", time: "11:40 AM" },
      { id: "m23", sender: "them", text: "See you in the evening meetup! ☕", time: "11:45 AM" }
    ]
  },
  {
    id: "chat_3",
    user: {
      id: "u_3",
      name: "Nusrat Jahan",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80",
      isOnline: false,
      lastSeen: "Active 2h ago"
    },
    unreadCount: 0,
    lastMessage: {
      text: "You: Thank you so much! ❤️",
      sender: "me",
      timestamp: "Yesterday"
    },
    messages: [
      { id: "m31", sender: "them", text: "Great job on the story feature!", time: "Yesterday" },
      { id: "m32", sender: "me", text: "Thank you so much! ❤️", time: "Yesterday" }
    ]
  }
];

export const initialNotifications = [
  {
    id: "notif_1",
    type: "friend_request",
    user: {
      name: "Kazi Ashikur Rahman",
      avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80",
      mutualFriends: 14
    },
    text: "sent you a friend request.",
    timeAgo: "10m",
    unread: true,
    actionNeeded: true
  },
  {
    id: "notif_2",
    type: "reaction",
    user: {
      name: "Tanjila Akter",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80"
    },
    text: "loved your photo: 'Weekend beach trip with boys'",
    timeAgo: "45m",
    unread: true,
    badge: "❤️"
  },
  {
    id: "notif_3",
    type: "comment",
    user: {
      name: "Rakibul Islam",
      avatar: "https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80"
    },
    text: "commented: 'The new UI is super slick! Congrats 🎉'",
    timeAgo: "2h",
    unread: true,
    badge: "💬"
  },
  {
    id: "notif_4",
    type: "mention",
    user: {
      name: "Nusrat Jahan",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80"
    },
    text: "mentioned you in a post in Bangladesh Tech Developers group.",
    timeAgo: "5h",
    unread: false,
    badge: "@"
  },
  {
    id: "notif_5",
    type: "group",
    user: {
      name: "The FacePost Community",
      avatar: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=150&auto=format&fit=crop&q=80"
    },
    text: "posted 8 new updates in The FacePost Official Hub.",
    timeAgo: "1d",
    unread: false,
    badge: "👥"
  }
];
