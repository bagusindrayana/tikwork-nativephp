import './bootstrap';
// import { share } from '#nativephp';

const links = document.querySelectorAll('a.apply');

links.forEach(link => {
    link.addEventListener('click', async (e) => {
        e.preventDefault();
        // ... rest of your logic
        try {
            // share({
            //     title: 'Apply for job',
            //     text: 'Apply for job',
            //     url: link.href
            // });

            try {
                // Use the standard browser API (supported by NativePHP Mobile)
                await navigator.share({
                    title: 'Apply for job',
                    text: 'Apply for job',
                    url: link.href
                });
            } catch (error) {
                console.log('Error sharing:', error);
            }
        } catch (error) {
            console.log(error);
        }
    });
});