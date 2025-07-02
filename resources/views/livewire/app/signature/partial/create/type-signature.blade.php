<div 
    x-data="typeNewSignature"
    @typenewsignatureshow.window="showType"
    @typenewsignaturecreate.window="statusCreateSignature($event)"
    @click.away="hideType"
    
    style="visibility: hidden; opacity: 0"
    :style="modalType ? `visibility: visible; opacity: 1` : `visibility: hidden; opacity: 0` "
    class="wrapper-modalCreateNewSignature fixed z-20 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 select-none transition-all w-screen h-screen bg-gray-800/30 flex items-center justify-center"
    :class="modalType ? `scale-100` : `scale-90` "
    
    {{-- style="visibility: hidden; opacity: 0"
    :style="modalType ? `visibility: visible; opacity: 1` : `visibility: hidden; opacity: 0` "
    class="wrapper-modalCreateNewSignature fixed z-20 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 select-none transition-all w-screen h-screen bg-gray-800/30 flex items-center justify-center"
        --}}
    data-modal-name="type-signature"
    wire:ignore
    >
    
    <div 
        class="ctr-modalCreateNewSignature bg-white shadow-md shadow-black/60 w-[32rem] rounded-lg overflow-hidden transition-all"
        {{-- style="visibility: hidden; opacity: 0"
        :style="modalType ? `visibility: visible; opacity: 1` : `visibility: hidden; opacity: 0` "
        :class="modalType ? `scale-100` : `scale-90` " --}}
        >
        <div class="cModalCreateNewSignature">
            
            <div class="headerModalCreateNewSignature px-4 pt-4">
                <div class="textHeader flex items-center justify-center">
                    <div class="txHeader text-lg font-medium">
                        <p>Type</p>
                    </div>
                </div>
            </div>
            
            <div class="mainModalCreateNewSignature my-2 h-[38rem] overflow-auto space-y-4 px-4">
                
                <div class="group-inputType grid grid-cols-5 items-center gap-2">
                    <div class="item-mainModal col-span-3" data-item-name="input-signature">
                        <div class="headerItem flex items-center justify-between gap-2">
                            <div class="tx text-sm">
                                <p>Signature</p>
                            </div>
                        </div>
                        
                        <div class="inputTypeSignature mt-1 border-2 border-slate-400 rounded-lg focus-within:border-blue-600">
                            <div class="input">
                                <input 
                                    type="text" 
                                    name="input_signature_type" 
                                    placeholder="Write your full name"
                                    data-input-type="signature" 
                                    @input="updateTypeText"
                                    class="p-2 w-full text-sm bg-transparent outline-none border-none"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="item-mainModal col-span-2" data-item-name="input-paraf">
                        <div class="headerItem flex items-center justify-between gap-2">
                            <div class="tx text-sm">
                                <p>Paraf</p>
                            </div>
                        </div>
                        
                        <div class="inputTypeParaf mt-1 border-2 border-slate-400 rounded-lg focus-within:border-blue-600">
                            <div class="input">
                                <input 
                                    type="text" 
                                    name="input_paraf_type" 
                                    placeholder="Write your initial"
                                    data-input-type="paraf" 
                                    @input="updateTypeText"
                                    class="p-2 w-full text-sm bg-transparent outline-none border-none"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="group-selectStyleCreateNow grid grid-cols-5 gap-2 items-center relative">
                    
                    <!-- Style -->
                    <div 
                        class="item-mainModal col-span-3" 
                        data-item-name="pick-font"
                        {{-- x-data.self="{modalItem: false}" --}}
                        >
                        {{-- <div class="headerItem">
                            <div class="tx text-sm font-light">
                                <p>Style</p>
                            </div>
                        </div> --}}
                        <div class="act-selectStyleType">
                            <button
                                type="button"
                                {{-- class="block border border-slate-400 px-4 py-2 rounded-lg w-52" --}}
                                class="block py-2 w-full border-b border-transparent hover:border-slate-400"
                                @click="modalChildStyle = !modalChildStyle"
                                @click.stop
                            >
                                <div class="cButtonAct flex items-center justify-between gap-2">
                                    <div class="textButton flex-grow">
                                        <div class="tx flex items-center justify-between">
                                            <p x-text="selectStyle.text" :style="`font-family: var(${selectStyle.font})`">Select Style</p>
                                            {{-- <p x-text="textLocal" class="text-sm">Select Style</p> --}}
                                            {{-- <p class="exampleText" style="display: none" :style="textLocal != 'Select Style' ? `font-family: var(${fontLocal})` : 'display: none' " >Example</p> --}}
                                        </div>
                                    </div>
                                    <div class="iconButton shrink-0 ">
                                        <div class="icon">
                                            <i class="fas fa-angle-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        
                        <div 
                            @click.away="modalChildStyle = false"
                            class="wrapper-selectStyleType absolute z-[2] top-full left-0 w-full pt-0.5 pb-2 pr-2 pl-1 mt-1 bg-[#f1f1f1] rounded-lg overflow-c overflow-c-gray transition-all shadow-inner shadow-black/10"
                            
                            style="visibility: hidden; opacity: 0"
                            :style="modalChildStyle ? `visibility: visible; opacity: 1; scale: 1` : `visibility: hidden; opacity: 0` "
                            :class="modalChildStyle ? `h-36 overflow-auto` : `h-0 pointer-events-none overflow-hidden` "
                            >
                            
                            <div class="selectStyleType space-y-0.5">
                                <template x-for="style in styles">
                                    
                                    <div class="itemPickStyle group">
                                        <label 
                                            class="labelPickStyle cursor-pointer block px-4 py-1 rounded-lg bg-white hover:bg-blue-100 group-has-[:checked]:bg-blue-100 shadow-sm shadow-black/40">
                                            <div class="cLabelPickStyle flex items-center justify-between gap-2">
                                                <div class="textStyle flex-grow">
                                                    <div class="tx flex items-center justify-between text-lg">
                                                        {{-- <p class="nameText flex-grow text-sm" x-text="style.text"></p> --}}
                                                        <p class="nameText" x-text="style.text" :style="`font-family: var(${style.font})`"></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="iconSelect shrink-0 size-6 flex items-center justify-center invisible opacity-0 group-has-[:checked]:visible group-has-[:checked]:opacity-100">
                                                    <div class="icon text-lg group-has-[:checked]:text-blue-600">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <input 
                                                type="radio" 
                                                name="pickStyleTypeNewSignature" 
                                                class="sr-only" 
                                                :value="style.value"
                                                :checked="style.default"  
                                                @change="updateTypeStyle">
                                        </label>
                                    </div>
                                    
                                </template>
                                
                            </div>
                        </div>
                    </div>
                    
                    <!-- Color -->
                    <div class="item-mainModal flex items-center justify-end gap-4 col-span-2" data-item-name="pick-color">
                        {{-- <div class="headerItem">
                            <div class="tx text-sm font-light">
                                <p>Color</p>
                            </div>
                        </div> --}}
                        <div class="listPickColor flex items-center flex-wrap gap-1">
                            <template x-for="color in colors">
                                
                                <div class="itemPickColor group">
                                    <label
                                        class="cursor-pointer block p-0.5 rounded-full outline outline-1 outline-gray-400 hover:outline-2 hover:outline-blue-600 group-has-[:checked]:outline-2 group-has-[:checked]:outline-blue-600 group-has-[:checked]:bg-blue-50"
                                    >
                                        <div class="cLabelPickCOlor">
                                            <div class="bulletColor">
                                                <div class="color flex items-center justify-center size-6 rounded-full">
                                                    <i class="size-3/4 rounded-full " :class="`bg-[${color.color}]`"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <input 
                                            class="sr-only"
                                            type="radio" 
                                            name="pickColorTypeNewSignature" 
                                            :value="color.color"
                                            :checked="color.default"
                                            @change="updateTypeColor"
                                            >
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                </div>
                
                <div class="group-canvasCreate space-y-2">
                    {{-- Canvas Signature --}}
                    <div class="item-mainModal size-fit" data-item-name="signature">
                        <div class="headerItem flex items-center justify-between gap-2">
                            <div class="tx text-sm">
                                <p>Signature</p>
                            </div>
                        </div>
                        
                        <div class="typeSignature mt-1 border border-gray-300 size-fit relative rounded-lg">
                            <div class="canvasDraw w-96 p-2">
                                <canvas 
                                    class="w-full h-full"
                                    data-set-type="signature"
                                    {{-- data-set-signature-aspect="16/9" --}}
                                    ></canvas>
                            </div>
                            
                        </div>
                    </div>
                    
                    {{-- Canvas Paraf --}}
                    <div class="item-mainModal size-fit" data-item-name="paraf">
                        <div class="headerItem flex items-center justify-between gap-2">
                            <div class="tx text-sm">
                                <p>Paraf</p>
                            </div>
                        </div>
                        
                        <div class="typeSignature mt-1 border border-gray-300 size-fit relative rounded-lg">
                            <div class="canvasDraw w-52 p-2 aspect-[1/1]">
                                <canvas 
                                    class="w-full h-full"
                                    data-set-type="paraf"
                                    {{-- data-set-signature-aspect="16/9" --}}
                                    ></canvas>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                
            </div>
            
            <div class="actionModalCreateNewSignature flex items-center justify-end gap-2 bg-white px-4 pb-4">
                <div class="act-cancelModalCreateNewSignature">
                    <button 
                        class="w-32 py-2 rounded-md hover:bg-red-100 outline-none hover:outline-1 hover:outline-red-600"
                        type="button"
                        data-action-pad-type="cancel"
                        @click="hideType"
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
                        @click="saveNewType"
                        x-bind:disabled="!saveType"
                        disabled
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
    </div>
</div>

@once
    @push('dashboard-body-script')
        <script data-navigate-once>
            Alpine.data('typeNewSignature', () => {
                
                const templateColors = JSON.parse('{!! $acceptColor !!}');
                const templateStyles = JSON.parse('{!! $acceptStyle !!}');
                const ruleType = JSON.parse('{!! $ruleType !!}');
                
                const acceptType = ['signature', 'paraf'];
                
                return {
                    modalType: false,
                    modalChildStyle: false,
                    saveType: false,
                    
                    typeSignature: [],
                    selectColor: templateColors.find(x => x.default),
                    selectStyle: templateStyles.find(x => x.default),
                    colors: templateColors,
                    styles: templateStyles,
                    
                    init() {
                        for (const key of acceptType) {
                            const setData = { key, value: null };
                            
                            this.typeSignature.push(setData);
                        };
                        
                        this.initResizeCanvas();
                    },
                    
                    showType() {
                        this.modalType = true;
                    },
                    
                    hideType() {
                        this.modalType = false;
                        this.resetType();
                    },
                    
                    updateTypeColor($e) {
                        const $element = $jq($e.currentTarget);
                        if (!$element) return;
                        
                        const value = $element.val();
                        if (! value) return;
                        
                        const accept = templateColors.find(x => x.color == value);
                        if (! accept) return;
                        
                        this.selectColor = accept;
                        
                        this.updateStyleColorCanvas();
                    },
                    
                    updateTypeStyle($e) {
                        const $element = $jq($e.currentTarget);
                        if (!$element) return;
                        
                        const value = $element.val();
                        if (! value) return;
                        
                        const accept = templateStyles.find(x => x.value == value);
                        if (! accept) return;
                        
                        this.selectStyle = accept;
                        
                        this.updateStyleColorCanvas();
                    },
                    
                    updateStyleColorCanvas() {
                        const $root = $jq(this.$root);
                        const $inputs = $root.find('input[data-input-type]');
                        
                        for (const input of $inputs) {
                            input.dispatchEvent(new Event('input'));
                        }
                    },
                    
                    updateTypeText($e) {
                        const $element = $jq($e.currentTarget);
                        const inputType = $element.data('inputType');
                        const rule = ruleType.find(x => x.key == inputType);
                        
                        if (! (acceptType.some(x => x == inputType) || rule ) ) return console.log('Type not valid');
                        
                        let value = $element.val();
                        let valueUpdate = value;
                        this.typeSignature.find(x => x.key == inputType).value = value;
                        
                        if (! value) valueUpdate = 'Example';
                        if (value.length >= rule.maxValue) return $element.val(value.slice(0, rule.maxValue));
                        
                        this.updateTypeCanvas(valueUpdate, inputType);
                        this.checkNewType();
                    },
                    
                    updateTypeCanvas(text = 'Nothing', type) {
                        const $root = $jq(this.$root);
                        const canvases = $root.find(`[data-set-type="${type}"]`);
                        const rule = ruleType.find(x => x.key == type);
                        const select = this.typeSignature.find(x => x.key == type);
                        
                        console.log('------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
                        console.log('TEXT: ', text);
                        console.log('------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
                        
                        for (const canvas of canvases) {
                            
                            const ctx = canvas.getContext('2d');
                            const rect = canvas.getBoundingClientRect();
                            canvas.width = rect.width;
                            canvas.height = rect.height;
                            
                            // ctx.fillStyle = "white"; // background
                            // ctx.fillRect(0, 0, rect.width, rect.height); // reset
                            ctx.clearRect(0, 0, rect.width, rect.height);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            
                            const maxWidthCanvas = rect.width - (rect.width * rule.paddingX);
                            const maxHeightCanvas = rect.height - (rect.height * rule.paddingY);
                            
                            let fontSize = rule.maxFontSize;
                            let textWidth = 0;
                            let textHeight = 0;
                            
                            let postX = rect.width / 2;
                            let postY = rect.height / 2;
                            
                            do {
                                ctx.font = `${fontSize}px ${this.selectStyle.value}`;
                                
                                const metrics = ctx.measureText(text);
                                
                                textWidth = metrics.width;
                                textHeight = metrics.fontBoundingBoxAscent + metrics.fontBoundingBoxDescent;
                                
                                // postX = ( rect.width - textWidth ) / 2;
                                // postY = ( rect.height - textHeight ) / 2;
                                
                                fontSize--;
                                
                            } while( (textWidth > maxWidthCanvas || fontSize > maxHeightCanvas) && fontSize >= rule.minFontSize );
                            
                            ctx.font = `${fontSize}px ${this.selectStyle.value}`;
                            
                            ctx.fillStyle = this.selectColor.color || '#000';
                            ctx.fillText(text, postX, postY);
                            
                            select.pad = canvas;
                            
                        }
                        
                    },
                    
                    initResizeCanvas() {
                        const $root = $jq(this.$root);
                        const canvases = $root.find(`[data-set-type]`);
                        
                        for (const canvas of canvases) {
                            const key = canvas.dataset.setType;
                            const rect = canvas.getBoundingClientRect();
                            
                            canvas.width = rect.width;
                            canvas.height = rect.height;
                            
                            this.updateTypeCanvas('Example', key);
                        }
                    },
                    
                    resetType() {
                        const $root = $jq(this.$root);
                        
                        this.selectColor = templateColors.find(x => x.default);
                        this.selectStyle = templateStyles.find(x => x.default);
                        
                        const $input = $root.find('input[data-input-type]');
                        $input.val('');
                        
                        const $inputColor = $root.find(`input[type="radio"][name="pickColorTypeNewSignature"][value="${this.selectColor.color}"]`);
                        $inputColor.prop('checked', true);
                        
                        const $inputStyle = $root.find(`input[type="radio"][name="pickStyleTypeNewSignature"][value="${this.selectStyle.value}"]`);
                        $inputStyle.prop('checked', true);
                        
                        this.updateStyleColorCanvas();
                        this.typeSignature = this.typeSignature.map( ({ key }) => ({
                            key,
                            value: null
                        }) );
                        
                        this.checkNewType();
                    },
                    
                    saveNewType() {
                        // if (! this.modalType) return console.log('Something error modal');
                        // console.log('Save new type');
                        console.log('---------------------------------------------');
                        console.log('Save new type');
                        console.log('---------------------------------------------');
                        const $token = '{{ csrf_token() }}';
                        
                        const arrData = [];
                        
                        for (const type of this.typeSignature) {
                            console.log(type);
                            const dataPadURL = type.pad.toDataURL();
                            
                            const tempData = {
                                key: type.key,
                                pad_json: null,
                                pad_images: [dataPadURL],
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
                        
                        this.$wire.saveType(dataSave);
                        console.log('---------------------------------------------');
                        console.log('---------------------------------------------');
                    },
                    
                    checkNewType() {
                        const $root = this.$root;
                        let checkedValue = 0;
                        
                        for (const key of acceptType) {
                            const select = this.typeSignature.find(x => x.key == key);
                            if (! select) continue;
                            if (! select.value) continue;
                            
                            checkedValue += 1;
                        }
                        
                        if (checkedValue != acceptType.length) { 
                            this.saveType = false; 
                            return false 
                        }
                        
                        this.saveType = true;
                        return true;
                    },
                    
                    statusCreateSignature($e) {
                        const detail = $e.detail;
                        if (! $e.detail.length ) return console.log('status undefined');
                        
                        const detailStatus = $e.detail[0];
                        console.log(detailStatus);
                        if (! detailStatus.status ) return this.updateStyleColorCanvas();
                        // if (! detailStatus.status ) return this.redrawCanvas();
                        
                        // for (const key of acceptPad) {
                        //     this.resetPad(key);
                        // } 
                        
                        this.hideType();
                        
                        // this.hideDraw();
                    },
                    
                    
                };
                
            });
            
        </script>
    @endpush
@endonce