## WhatsApp ChatGPT Integration

### Setup
- Login/Signup to twilio
- Setup Sandbox Configuration [Webhook and Callbackurl](https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn?frameUrl=%2Fconsole%2Fsms%2Fwhatsapp%2Flearn%3Fx-target-region%3Dus1)
    - Webhook:
        - url: `<ProjectDomain>/incoming-message`
        - method: POST
    - Callback:
        - url: `<ProjectDomain>/send-message`
        - method: POST
- Sandbox: Scan QR code to add your number to Sandbox Participants
- Set configuration in `.env` File:
    -
    ```
    TWILIO_SID="<TWILIO_SID>"
    TWILIO_TOKEN="<TWILIO_TOKEN>"
    TWILIO_WP_NUMBER="+<TWILIO_WP_NUMBER>"
    OPENAI_SECRET_KEY="<OPENAI_SECRET_KEY>"
    ```
### [Demo Video](https://drive.google.com/file/d/1rY_ORk-PtB9dtAKH6O8HJXZTkbuVgxez/view?usp=sharing)
