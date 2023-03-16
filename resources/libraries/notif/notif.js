/* 
 * Accessible and responsive notifications
 *
 * Depends on jQuery slim and Tailwind
 *
 * TO DO
 * Add swipe handlers for closing
 *
 */

class Notif {

	$container = $(`
        <div aria-live="assertive" id="notif-container"
            class="min-w-[300px] fixed top-3 right-3 flex flex-col items-start z-50"
        ></div>`);

	good = $(`
		<div 
			aria-atomic="true"
			aria-live="polite"
			id="notif-success" 
            class="toast text-gray-800 dark:text-gray-400 border border-gray-600 bg-white dark:bg-gray-900 flex opacity-0 mb-2 shadow-sm rounded-md transition-all overflow-hidden relative w-full" 
			role="status">
            <div class="w-2 bg-green-500">&nbsp;</div>
			<div class="px-4 py-2 flex justify-between items-center w-full">
				<span class="mr-4"></span>
				<button  
					 aria-label="Close"
					 type="button" 
					 class="flex items-center justify-center w-4 h-4 p-2">
                     <b>x</b>
				</button>
			</div>
		</div>
	`);

	bad = $(`
		<div 
			aria-atomic="true"
			aria-live="assertive"
			id="notif-error" 
            class="toast text-gray-800 dark:text-gray-400 border border-gray-600 bg-white dark:bg-gray-900 flex opacity-0 mb-2 shadow-sm rounded-md transition-all overflow-hidden relative w-full" 
			role="alert">
            <div class="w-2 bg-red-500">&nbsp;</div>
			<div class="px-4 py-2 flex justify-between items-center w-full">
				<span class="mr-4"></span>
				<button  
					 aria-label="Close"
					 type="button" 
					 class="flex items-center justify-center w-4 h-4 p-2">
                     <b>x</b>
				</button>
			</div>
		</div>
	`);

	constructor() {
		this.$container.appendTo($('body'));
	}

	i(message, good=true, delay=6000) {
		(good ? this.good : this.bad)
			.clone()
			.hide()
			.appendTo(this.$container)
			.on('notif-show', (e) => this.handleShow(e, delay))
            .show()
			.removeClass('opacity-0').addClass('opacity-100')
            .trigger('notif-show')
			.find('span').html(message)
			.next().click((e) => this.handleClose(e));
		this.$container.show();
	}

	handleShow(e, delay) {
		setTimeout((n) => this.hide(n), delay, $(e.target));
	};

	hide($n) {
		$n.removeClass('opacity-100')
          .addClass('opacity-0')
          .delay(200)
          .remove();
	}

	handleClose(e) {
		this.hide($(e.target).closest('.toast'));
	}
}

const notif = new Notif();