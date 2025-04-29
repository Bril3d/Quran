<?php 
// Set page title for layout
$pageTitle = 'استشارة قرآنية';
?>

<div class="chat-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="section-header">
                    <h2>استشارة قرآنية</h2>
                    <p>أخبرنا عن حالتك المزاجية وسنقدم لك التوصيات القرآنية المناسبة</p>
                </div>
                
                <div class="chat-wrapper">
                    <div class="chat-header">
                        <h3>تحدث معنا</h3>
                    </div>
                    
                    <div class="chat-messages">
                        <div class="chat-message bot">
                            <div class="message-content">
                                مرحباً بك! أنا هنا لمساعدتك في العثور على السور القرآنية التي قد تناسب حالتك المزاجية أو المشاعر التي تمر بها. كيف تشعر اليوم؟
                            </div>
                        </div>
                        
                        <?php if(!empty($chatHistory)): ?>
                            <?php foreach($chatHistory as $chat): ?>
                                <div class="chat-message user">
                                    <div class="message-content">
                                        <?= $chat['user_message'] ?>
                                    </div>
                                </div>
                                <div class="chat-message bot">
                                    <div class="message-content">
                                        <?= $chat['bot_message'] ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <form id="chat-form" class="chat-input">
                        <input type="text" id="message-input" placeholder="اكتب رسالتك هنا..." autocomplete="off">
                        <button type="submit">إرسال</button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="suggestion-panel">
                    <div class="card">
                        <div class="card-header">
                            <h4>اقتراحات للمحادثة</h4>
                        </div>
                        <div class="card-body">
                            <p>يمكنك البدء بإحدى العبارات التالية:</p>
                            <ul class="suggestion-list">
                                <li><button class="suggestion-btn">أشعر بالحزن اليوم</button></li>
                                <li><button class="suggestion-btn">أنا قلق بشأن المستقبل</button></li>
                                <li><button class="suggestion-btn">أشعر بالسعادة والامتنان</button></li>
                                <li><button class="suggestion-btn">أحتاج إلى الهدوء والسكينة</button></li>
                                <li><button class="suggestion-btn">أبحث عن الهداية</button></li>
                                <li><button class="suggestion-btn">أشعر بالإحباط</button></li>
                                <li><button class="suggestion-btn">أنا في حيرة من أمري</button></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="recommendation-container mt-4">
                        <!-- Recommendations will be added here dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Suggestion buttons functionality
    document.querySelectorAll('.suggestion-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const messageInput = document.getElementById('message-input');
            messageInput.value = this.textContent;
            messageInput.focus();
        });
    });
</script>

<style>
    .suggestion-list {
        list-style: none;
        padding: 0;
    }
    
    .suggestion-list li {
        margin-bottom: 10px;
    }
    
    .suggestion-btn {
        background-color: #f8f9fa;
        border: 1px solid #e2e6ea;
        border-radius: 20px;
        padding: 8px 15px;
        width: 100%;
        text-align: right;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .suggestion-btn:hover {
        background-color: #e2e6ea;
    }
    
    .dark-theme .suggestion-btn {
        background-color: #2a2a2a;
        border-color: #444;
        color: #f5f5f5;
    }
    
    .dark-theme .suggestion-btn:hover {
        background-color: #333;
    }
    
    .chat-messages {
        height: 400px;
    }
    
    /* Typing indicator */
    .typing-indicator .message-content {
        padding: 10px;
    }
    
    .typing-indicator .dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #888;
        margin: 0 2px;
        animation: wave 1.3s linear infinite;
    }
    
    .typing-indicator .dot:nth-child(2) {
        animation-delay: -1.1s;
    }
    
    .typing-indicator .dot:nth-child(3) {
        animation-delay: -0.9s;
    }
    
    @keyframes wave {
        0%, 60%, 100% {
            transform: initial;
        }
        30% {
            transform: translateY(-5px);
        }
    }
</style> 