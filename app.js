document.addEventListener("DOMContentLoaded", function() 
{
    var startRecordBtn = document.getElementById('start-record-btn');
    var status = document.getElementById('status');

    var recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'hu-HU';
    let isRecognizing = false;

    function convertTextToNumber(text) {
        const numberWords = {
            "nulla": 0, "egy": 1, "kettő": 2, "három": 3, "négy": 4, 
            "öt": 5, "hat": 6, "hét": 7, "nyolc": 8, "kilenc": 9
        };

        
        return numberWords[text.toLowerCase()] !== undefined ? numberWords[text.toLowerCase()] : text;
    }

    recognition.onstart = function() {
        status.textContent = "Hallgat...";
        isRecognizing = true;
    };

    recognition.onspeechend = function() {
        status.textContent = "Feldolgozás...";
        recognition.stop();
    };

    recognition.onresult = function(event) {
        var transcript = event.results[0][0].transcript.trim();
        var convertedNumber = convertTextToNumber(transcript);

        status.textContent = "Felismert szöveg: " + transcript + " (Átkonvertálva: " + convertedNumber + ")";

        
        fetch('save_number.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ number: convertedNumber })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                status.textContent = "Elem elmentve!";
                location.reload(); 
            } else {
                status.textContent = "Hiba történt: " + data.message;
            }
        })
        .catch(error => {
            status.textContent = "Hiba történt!";
            console.error('Hiba:', error);
        });
    };

    startRecordBtn.addEventListener('click', function() {
        if (!isRecognizing) {
            recognition.start();
        } else {
            status.textContent = "Már folyamatban van a felismerés.";
        }
    });

    recognition.onend = function() {
        isRecognizing = false;
    };
});


