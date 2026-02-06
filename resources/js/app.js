import './bootstrap';
// import { share } from '#nativephp';

const links = document.querySelectorAll('a.apply');

links.forEach(link => {
    link.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
            // Use the standard browser API (supported by NativePHP Mobile)
            await navigator.share({
                title: 'Apply for job',
                text: 'Apply for job',
                url: link.href
            });
        } catch (error) {
            console.log('Error sharing:', error);
            // Use the Clipboard API to write the text
            navigator.clipboard.writeText(link.href).then(() => {
                // Alert the user that the text has been copied (optional)
                alert("Copied the url: " + link.href);
            }).catch(err => {
                // Handle any potential errors
                console.error('Could not copy text: ', err);
                alert(err);
            });
        }
    });
});