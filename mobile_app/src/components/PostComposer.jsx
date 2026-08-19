import React, { useState, useRef } from 'react';
import { Image, Video, Smile, Globe, Users, Lock, X, Tag, MapPin, Camera } from 'lucide-react';

export default function PostComposer({ 
  currentUser, 
  onAddNewPost, 
  isOpenModal, 
  onOpenModal, 
  onCloseModal 
}) {
  const [postText, setPostText] = useState('');
  const [selectedImage, setSelectedImage] = useState(null);
  const [privacy, setPrivacy] = useState('public');
  const [feeling, setFeeling] = useState('');
  const [showFeelingsPicker, setShowFeelingsPicker] = useState(false);
  const fileInputRef = useRef(null);

  const feelingsList = [
    { label: 'Happy', emoji: '😊' },
    { label: 'Blessed', emoji: '😇' },
    { label: 'Loved', emoji: '🥰' },
    { label: 'Excited', emoji: '🤩' },
    { label: 'Crazy', emoji: '🤪' },
    { label: 'Thankful', emoji: '🙏' },
    { label: 'Proud', emoji: '😎' }
  ];

  const handleImageSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        setSelectedImage(reader.result);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleCreatePost = (e) => {
    if (e) e.preventDefault();
    if (!postText.trim() && !selectedImage) return;

    const content = feeling ? `${postText} — feeling ${feeling}` : postText;

    onAddNewPost({
      content,
      image: selectedImage,
      privacy
    });

    setPostText('');
    setSelectedImage(null);
    setFeeling('');
    setShowFeelingsPicker(false);
    onCloseModal();
  };

  return (
    <>
      {/* Feed Composer Trigger Card */}
      <div className="composer-card">
        <div className="composer-top">
          <img 
            src={currentUser.avatar} 
            alt={currentUser.name} 
            className="composer-avatar"
            onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
          />
          <button 
            className="composer-input-btn"
            onClick={onOpenModal}
          >
            What's on your mind, {currentUser.name?.split(' ')[0] || 'Friend'}?
          </button>
        </div>

        <div className="composer-actions">
          <button 
            className="composer-action-btn"
            onClick={() => {
              onOpenModal();
              setTimeout(() => fileInputRef.current?.click(), 100);
            }}
          >
            <Image size={18} color="#45bd62" />
            <span>Photo</span>
          </button>

          <button 
            className="composer-action-btn"
            onClick={() => {
              onOpenModal();
              setShowFeelingsPicker(true);
            }}
          >
            <Smile size={18} color="#f7b125" />
            <span>Feeling</span>
          </button>

          <button 
            className="composer-action-btn"
            onClick={onOpenModal}
          >
            <Video size={18} color="#f3425f" />
            <span>Live Video</span>
          </button>
        </div>
      </div>

      {/* Fullscreen / Interactive Modal Composer */}
      {isOpenModal && (
        <div className="modal-backdrop" onClick={onCloseModal}>
          <div className="modal-sheet" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <span className="modal-title">Create Post</span>
              <button className="icon-btn" onClick={onCloseModal}>
                <X size={20} />
              </button>
            </div>

            <form onSubmit={handleCreatePost} style={{ display: 'flex', flexDirection: 'column', flex: 1 }}>
              <div className="modal-body">
                {/* Author Details & Privacy Selector */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                  <img 
                    src={currentUser.avatar} 
                    alt={currentUser.name} 
                    style={{ width: 44, height: 44, borderRadius: '50%', objectFit: 'cover' }}
                    onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
                  />
                  <div>
                    <div style={{ fontWeight: 700, fontSize: 15, display: 'flex', alignItems: 'center', gap: 4 }}>
                      <span>{currentUser.name}</span>
                      {feeling && (
                        <span style={{ fontSize: 13, fontWeight: 500, color: 'var(--text-secondary)' }}>
                          is feeling {feeling}
                        </span>
                      )}
                    </div>
                    
                    <div style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: 4,
                      backgroundColor: 'var(--bg-input)',
                      padding: '2px 8px',
                      borderRadius: 6,
                      fontSize: 12,
                      marginTop: 2,
                      fontWeight: 600
                    }}>
                      <Globe size={12} />
                      <span>{privacy === 'public' ? 'Public' : 'Friends'}</span>
                    </div>
                  </div>
                </div>

                {/* Text Input Area */}
                <textarea
                  className="modal-textarea"
                  placeholder={`What's on your mind, ${currentUser.name?.split(' ')[0] || 'Friend'}?`}
                  value={postText}
                  onChange={(e) => setPostText(e.target.value)}
                  autoFocus
                  rows={4}
                />

                {/* Selected Image Preview */}
                {selectedImage && (
                  <div style={{ position: 'relative', marginTop: 12, borderRadius: 12, overflow: 'hidden', border: '1px solid var(--border-color)' }}>
                    <img 
                      src={selectedImage} 
                      alt="Uploaded preview" 
                      style={{ width: '100%', maxHeight: 240, objectFit: 'cover' }} 
                    />
                    <button
                      type="button"
                      onClick={() => setSelectedImage(null)}
                      style={{
                        position: 'absolute',
                        top: 8,
                        right: 8,
                        backgroundColor: 'rgba(0,0,0,0.7)',
                        border: 'none',
                        color: 'white',
                        width: 28,
                        height: 28,
                        borderRadius: '50%',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        cursor: 'pointer'
                      }}
                    >
                      <X size={16} />
                    </button>
                  </div>
                )}

                {/* Feelings Picker Grid */}
                {showFeelingsPicker && (
                  <div style={{ marginTop: 12, padding: 10, backgroundColor: 'var(--bg-input)', borderRadius: 10 }}>
                    <span style={{ fontSize: 12, fontWeight: 700, color: 'var(--text-secondary)', display: 'block', marginBottom: 8 }}>
                      How are you feeling?
                    </span>
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
                      {feelingsList.map((f, i) => (
                        <button
                          key={i}
                          type="button"
                          onClick={() => {
                            setFeeling(`${f.label} ${f.emoji}`);
                            setShowFeelingsPicker(false);
                          }}
                          style={{
                            padding: '6px 12px',
                            borderRadius: 16,
                            border: '1px solid var(--border-color)',
                            backgroundColor: feeling.includes(f.label) ? 'var(--primary)' : 'var(--bg-card)',
                            color: feeling.includes(f.label) ? 'white' : 'var(--text-primary)',
                            fontSize: 13,
                            cursor: 'pointer',
                            display: 'flex',
                            alignItems: 'center',
                            gap: 4
                          }}
                        >
                          <span>{f.emoji}</span>
                          <span>{f.label}</span>
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Hidden File Input */}
                <input 
                  type="file" 
                  ref={fileInputRef}
                  onChange={handleImageSelect}
                  accept="image/*"
                  style={{ display: 'none' }}
                />

                {/* Add to Post Quick Toolbar */}
                <div style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  padding: '10px 14px',
                  borderRadius: 10,
                  border: '1px solid var(--border-color)',
                  marginTop: 16,
                  backgroundColor: 'var(--bg-card)'
                }}>
                  <span style={{ fontSize: 13, fontWeight: 600 }}>Add to your post</span>
                  <div style={{ display: 'flex', gap: 8 }}>
                    <button 
                      type="button" 
                      className="icon-btn" 
                      onClick={() => fileInputRef.current?.click()}
                      title="Add Photo"
                    >
                      <Image size={20} color="#45bd62" />
                    </button>
                    <button 
                      type="button" 
                      className="icon-btn" 
                      onClick={() => setShowFeelingsPicker(!showFeelingsPicker)}
                      title="Add Feeling"
                    >
                      <Smile size={20} color="#f7b125" />
                    </button>
                    <button 
                      type="button" 
                      className="icon-btn" 
                      onClick={() => setPrivacy(privacy === 'public' ? 'friends' : 'public')}
                      title="Privacy"
                    >
                      {privacy === 'public' ? <Globe size={20} color="#1877f2" /> : <Users size={20} color="#1877f2" />}
                    </button>
                  </div>
                </div>
              </div>

              {/* Submit Post Button */}
              <div className="modal-footer">
                <button
                  type="submit"
                  className="btn btn-primary"
                  style={{ width: '100%', padding: '12px 0', fontSize: 16, fontWeight: 700 }}
                  disabled={!postText.trim() && !selectedImage}
                >
                  Post
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
