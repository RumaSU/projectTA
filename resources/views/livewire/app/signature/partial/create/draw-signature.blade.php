<div 
    x-data="drawNewSignature"
    @drawnewsignatureshow.window="showDraw"
    @drawnewsignaturestroke.window="checkPadValue"
    {{-- @drawnewsignatureredraw.window="redrawSignatureCanvas" --}}
    @drawnewsignatureresizecanvas.window="resizeAllCanvas"
    @drawnewsignaturecreate.window="statusCreateSignature($event)"
    @click.away="hideDraw"
    {{-- x-on:click.away="hideDraw" --}}
    {{-- x-show="modalDraw" --}}
    
    {{-- style="visibility: hidden; opacity: 0; transform:scaleX(0.8) scaleY(0.8);" --}}
    {{-- :style="modalDraw ? `visibility: visible; opacity: 1; transform:scaleX(1) scaleY(1);` : `visibility: hidden; opacity: 0; transform:scaleX(0.8) scaleY(0.8);` " --}}
    
    style="visibility: hidden; opacity: 0"
    :style="modalDraw ? `visibility: visible; opacity: 1` : `visibility: hidden; opacity: 0` "
    class="wrapper-modalCreateNewSignature fixed z-20 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 select-none transition-all w-screen h-screen bg-gray-800/30 flex items-center justify-center"
    :class="modalDraw ? `scale-100` : `scale-90` "
    
    data-modal-name="draw-signature"
    wire:ignore
    >
    
    <div 
        class="ctr-modalCreateNewSignature bg-white shadow-md shadow-black/60 w-[32rem] p-4 rounded-lg relative overflow-hidden"
        >
        <div class="cModalCreateNewSignature">
            
            <div class="mainContentModalCreateNewSignature">
                <div class="headerModalCreateNewSignature">
                    <div class="textHeader flex items-center justify-center">
                        <div class="txHeader text-lg font-medium">
                            <p>Draw</p>
                        </div>
                    </div>
                </div>
                
                <div class="mainModalCreateNewSignature my-2 h-[36rem] space-y-4">
                    
                    {{-- Color --}}
                    <div class="item-mainModal" data-item-name="pick-color">
                        <div class="headerItem">
                            <div class="tx text-sm font-light">
                                <p>Color - <span class="font-medium" x-text="penColor.text"></span></p>
                            </div>
                        </div>
                        <div class="listPickColor min-h-12 mt-1 flex items-center flex-wrap gap-2">
                            <template x-for="color in colors">
                                
                                <div class="itemPickColor group">
                                    <label
                                        class="cursor-pointer block w-28 px-2 py-0.5 outline outline-1 outline-gray-400 hover:outline-2 hover:outline-blue-600 group-has-[:checked]:outline-2 group-has-[:checked]:outline-blue-600 group-has-[:checked]:bg-blue-50 rounded-md"
                                    >
                                        <div class="cLabelPickColor flex items-center gap-2">
                                            <div class="bulletColor">
                                                <div class="color flex items-center justify-center size-6 rounded-full p-[0.5px]">
                                                    <i class="size-3/4 rounded-full " :class="`bg-[${color.color}]`"></i>
                                                </div>
                                            </div>
                                            <div class="textColor">
                                                <div class="tx text-sm">
                                                    <p x-text="color.text"></p>
                                                </div>
                                            </div>
                                            
                                            <input 
                                                class="sr-only"
                                                type="radio" 
                                                name="pickColorDrawNewSignature" 
                                                :value="color.color"
                                                :checked="color.default"
                                                @change="changeColor(color.color)"
                                                >
                                        </div>
                                    </label>
                                </div>
                                
                            </template>
                        </div>
                    </div>
                    
                    {{-- Canvas Signature --}}
                    <div class="item-mainModal size-fit" data-item-name="signature">
                        <div class="headerItem flex items-center justify-between gap-2">
                            <div class="tx text-sm">
                                <p>Signature</p>
                            </div>
                            <div class="act-clearCanvas">
                                <button
                                    class="hidden"
                                    type="button"
                                    @click="resetPad('signature')"
                                    data-clear-pad="signature"
                                >
                                    <div class="cBtn">
                                        <div class="tx text-sm text-slate-700">
                                            <p>Clear</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <div class="drawSignature mt-1 border border-gray-300 size-fit relative rounded-lg" wire:ignore>
                            <div class="canvasDraw w-96 p-2" wire:ignore>
                                <canvas 
                                    class="w-full h-full"
                                    data-set-pad="signature"
                                    key="signature-pad"
                                    wire:ignore
                                    ></canvas>
                            </div>
                            
                            <div class="placeholderDraw absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none w-full" data-placeholder-pad="signature">
                                <div class="text text-sm text-black/50 text-center">
                                    <span>Draw signature here.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Canvas Paraf --}}
                    <div class="item-mainModal size-fit" data-item-name="paraf">
                        <div class="headerItem flex items-center justify-between gap-2">
                            <div class="tx text-sm">
                                <p>Paraf</p>
                            </div>
                            <div class="act-clearCanvas">
                                <button
                                    class="hidden"
                                    type="button"
                                    @click="resetPad('paraf')"
                                    data-clear-pad="paraf"
                                >
                                    <div class="cBtn">
                                        <div class="tx text-sm text-slate-700">
                                            <p>Clear</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <div class="drawParaf mt-1 border border-gray-300 size-fit relative rounded-lg" wire:ignore>
                            <div class="canvasDraw w-52 p-2 aspect-[1/1]" wire:ignore>
                                <canvas 
                                    class="w-full h-full"
                                    data-set-pad="paraf"
                                    wire:ignore
                                    {{-- data-set-signature-aspect="1/1" --}}
                                    ></canvas>
                            </div>
                            
                            <div class="placeholderDraw absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none w-full" data-placeholder-pad="paraf">
                                <div class="text text-sm text-black/50 text-center">
                                    <span>Draw paraf here.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="actionModalCreateNewSignature flex items-center justify-end gap-2">
                    <div class="act-cancelModalCreateNewSignature">
                        <button 
                            class="w-32 py-2 rounded-md hover:bg-red-100 outline-none hover:outline-1 hover:outline-red-600"
                            type="button"
                            data-action-pad-type="cancel"
                            @click="hideDraw"
                            >
                            <div class="cButtonActModal">
                                <div class="textAct">
                                    <div class="tx text-sm text-red-600">
                                        <p>Cancel</p>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="act-saveModalCreateNewSignature">
                        <button
                            class="w-32 py-2 rounded-md bg-[#FFCA28] [&:not(:disabled)]:hover:bg-yellow-500"
                            type="button"
                            data-action-pad-type="save"
                            x-bind:disabled="!savePads"
                            wire:loading.attr='disabled'
                            disabled
                            @click="saveNewPad"
                            >
                            <div class="cButtonActModal">
                                <div class="textAct">
                                    <div class="tx text-sm text-[#574300]">
                                        <p>Save</p>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            
            <div 
                class="w-full h-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 animate-pulse bg-gray-600/50 hidden"
                    wire:loading.class.remove='hidden'
                    {{-- wire:loading.class.add='hidden' --}}
                ></div>
            
        </div>
    </div>
</div>

@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            
            Alpine.data('drawNewSignature', () => {
                const templateColors = JSON.parse( '{!! $acceptColor !!}' );
                // const acceptPad = ['signature', 'paraf'];
                const acceptPad = JSON.parse('{!! $keyDraw !!}');
                
                console.log(templateColors);
                
                return {
                    modalDraw: false,
                    savePads: false,
                    drawPads: [],
                    penColor: templateColors.find(x => x.default),
                    colors: templateColors,
                    
                    init() {
                        this.initPads();
                    },
                    
                    initPads() {
                        const $root = this.$root;
                        const canvases = $jq($root).find('[data-set-pad]');
                        
                        for (const canvas of canvases) {
                            const $canvas = $jq(canvas);
                            const keyPad = $canvas.data('setPad');
                            
                            if (! keyPad) continue;
                            this.resizeCanvas(canvas);
                            
                            const pad = new SignaturePad(canvas, {
                                penColor: this.penColor.color,
                                backgroundColor: 'rgba(255,255,255,0)',
                            });
                            
                            pad.addEventListener("beginStroke", () => {
                                const placeholder = $jq(`[data-placeholder-pad="${keyPad}"]`);
                                const clear = $jq(`[data-clear-pad="${keyPad}"]`);
                                
                                placeholder.addClass('hidden').removeClass('block flex');
                                clear.addClass('block').removeClass('hidden');
                            });
                            
                            pad.addEventListener("endStroke", () => {
                                const updateStroke = new Event('drawnewsignaturestroke');
                                window.dispatchEvent(updateStroke);
                            });
                            
                            this.drawPads.push({key: keyPad, pad});
                        }
                    },
                    
                    showDraw() {
                        this.modalDraw = true;
                        
                        for (const key of acceptPad) {
                            this.resetPad(key);
                            
                            const select = this.drawPads.find(x => x.key == key);
                            if (! select) continue;
                            
                            const selectPad = select.pad;
                            selectPad.on();
                        }
                        
                    },
                    
                    hideDraw() {
                        this.modalDraw = false;
                        
                        setTimeout( () => {
                            for (const key of acceptPad) {
                                this.resetPad(key);
                                
                                const select = this.drawPads.find(x => x.key == key);
                                if (! select) continue;
                                
                                const selectPad = select.pad;
                                selectPad.off();
                            }
                        }, 1000);
                    },
                    
                    changeColor(color) {
                        const select = templateColors.find(x => x.color == color);
                        if (! select) return this.resetValue();
                        this.penColor = select;
                        
                        for ( const {key, pad} of this.drawPads ) {
                            console.log(key, pad);
                            pad.penColor = select.color;
                        }
                    },
                    
                    resetPad(key) {
                        const select = this.drawPads.find(x => x.key == key);
                        if (! key) return;
                        
                        const selectPad = select.pad;
                        const placeholder = $jq(`[data-placeholder-pad="${key}"]`);
                        const clear = $jq(`[data-clear-pad="${key}"]`);
                        
                        placeholder.addClass('block').removeClass('hidden');
                        clear.addClass('hidden').removeClass('block');
                        
                        selectPad.clear();
                        this.checkPadValue();
                    },
                    
                    redrawCanvas() {
                        for (const key of acceptPad) {
                            const select = this.drawPads.find(x => x.key == key);
                            if (!select) continue;
                            
                            try {
                                const dataURL = select.pad.toDataURL();
                                // select.pad.clear(); // optional
                                select.pad.fromDataURL(dataURL);
                            } catch (e) {
                                console.warn('Redraw gagal, kemungkinan canvas rusak:', e);
                            }
                        }
                    },
                    
                    saveNewPad() {
                        console.log('---------------------------------------------');
                        console.log('Save new pad');
                        console.log('---------------------------------------------');
                        const $token = '{{ csrf_token() }}';
                        
                        const arrData = [];
                        
                        for (const draw of this.drawPads) {
                            const dataPad = draw.pad.toData();
                            const dataPadURL = draw.pad.toDataURL();
                            const dataPadURLSVG = draw.pad.toDataURL("image/svg+xml");
                            
                            const tempData = {
                                key: draw.key,
                                pad_json: dataPad,
                                pad_images: [ dataPadURL, dataPadURLSVG],
                                // pad_url: dataPadURL,
                                // pad_svg: dataPadURLSVG,
                            };
                            
                            arrData.push(tempData);
                        }
                        
                        const dataSave = {
                            _token: $token,
                            value: arrData,
                        };
                        
                        console.log(dataSave);
                        
                        this.$wire.saveDraw(dataSave);
                        console.log('---------------------------------------------');
                        console.log('---------------------------------------------');
                    },
                    
                    resizeAllCanvas() {
                        const $root = $jq(this.$root);
                        const canvases = $jq($root).find('[data-set-pad]');
                        
                        for (const canvas of canvases) {
                            const $canvas = $jq(canvas);
                            
                            const keyPad = $canvas.data('setPad');
                            if (! keyPad) continue;

                            const select = this.drawPads.find(x => x.key == keyPad);
                            if (! select) continue;
                            
                            this.resizeCanvas(canvas);
                        }
                    },
                    
                    resizeCanvas(canvas) {
                        const rect = canvas.getBoundingClientRect();
                        canvas.width = rect.width;
                        canvas.height = rect.height;
                        
                        const dpr = window.devicePixelRatio || 1;
                        canvas.width = rect.width * dpr;
                        canvas.height = rect.height * dpr;
                        
                        canvas.getContext('2d').scale(dpr, dpr);
                    },
                    
                    checkPadValue() {
                        
                        let checkedValue = 0;
                        for (const key of acceptPad) {
                            const select = this.drawPads.find(x => x.key == key);
                            if (! select) continue;
                            
                            const selectPad = select.pad;
                            if (selectPad.isEmpty()) continue;
                            
                            checkedValue += 1;
                        }
                        
                        if (checkedValue != acceptPad.length) { 
                            this.savePads = false; 
                            return false 
                        }
                        
                        this.savePads = true;
                        return true;
                        
                    },
                    
                    statusCreateSignature($e) {
                        const detail = $e.detail;
                        if (! $e.detail.length ) return console.log('status undefined');
                        
                        const detailStatus = $e.detail[0];
                        if (! detailStatus.status ) return this.redrawCanvas();
                        
                        // for (const key of acceptPad) {
                        //     this.resetPad(key);
                        // } 
                        
                        this.hideDraw();
                    },
                    
                };
                
            });
            
        </script>
        
        <script data-navigate-once>
            // Livewire.hook('morph',  ({ el, component }) => {
            //     // Runs just before the child elements in `component` are morphed
            //     console.log('Livewire morph');
            // });
            
            // Livewire.hook('morphed',  ({ el, component }) => {
            //     console.log('Livewire morphed');
            //     // Runs after all child elements in `component` are morphed
            // });
            Livewire.hook('morph', ({ component }) => {
                // console.log('Start morph:', component)
            })

            Livewire.hook('morphed', ({ component }) => {
                // console.log('End morph:', component);
                // window.dispatchEvent(new Event('drawnewsignatureredraw'));
                window.dispatchEvent(new Event('drawnewsignatureresizecanvas'));
            });
            
            
            // Livewire.hook('morph.updating',  ({ el, component, toEl, skip, childrenOnly }) => {
            //     console.log('Livewire morph updating');
            //     console.dir('Updating:');
            //     console.dir(el);
            //     console.dir('→');
            //     console.dir(toEl);
            //     console.log(el.dataset);
            //     if (el.dataset.hasOwnProperty('noMorph')) {
            //         console.log('Skip Morph...............');
            //         skip();
            //     }
            // });
            
            // Livewire.hook('morph.updated', ({ el, component }) => {
            //     console.log('Livewire morph updated');
            //     //
            // });
            
            // Livewire.hook('morph.removing', ({ el, component, skip }) => {
            //     console.log('Livewire morph removing');
            //     //
            // });
            
            // Livewire.hook('morph.removed', ({ el, component }) => {
            //     console.log('Livewire morph removed');
            //     //
            // });
            
            // Livewire.hook('morph.adding',  ({ el, component }) => {
            //     console.log('Livewire morph adding');
            //     //
            // });
            
            // Livewire.hook('morph.added',  ({ el }) => {
            //     console.log('Livewire morph added');
            //     // window.dispatchEvent('canvas_resized');
            //     //
            // });
            
        </script>
        
    @endpush
@endonce