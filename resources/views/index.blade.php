@extends('layouts.app')

@section('title', 'Agent Bookr - Lead Generation & Cold Calling Solutions for Real Estate Agents')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center justify-center relative" id="hero-section" style="padding-top: 100px;">
        <div class="text-center text-white z-10">
            <!-- Main Headlines -->
            <div class="mb-8">
                <h2 class="text-5xl md:text-6xl font-bold mb-2">Generate More Leads</h2>
                <h3 class="text-4xl md:text-5xl font-bold">Close More Deals</h3>
            </div>
            
            <!-- Get Started Button -->
            <div class="max-w-md mx-auto">
                <p class="text-white mb-6 text-lg">Ready to transform your real estate business?</p>
                <a href="#pricing" class="w-full px-8 py-4 bg-[#FFB703] text-[#2F3E46] font-semibold text-lg rounded-lg hover:bg-[#FFB703]/90 transition duration-300 shadow-lg transform hover:scale-105 inline-block text-center">
                    <i class="fas fa-rocket mr-2"></i>
                    Start Your Free Trial
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Text Content -->
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-black mb-6 font-montserrat">
                        Everything You Need to Scale Your Real Estate Business
                    </h2>
                    <p class="text-lg text-black leading-relaxed font-montserrat">
                        Agent Bookr provides comprehensive lead generation tools, proven cold calling scripts, and expert tutorials designed specifically for real estate agents. Our platform combines cutting-edge technology with time-tested strategies to help you generate more leads, close more deals, and grow your business faster than ever before.
                    </p>
                </div>
                
                <!-- Right Side - Video Thumbnail -->
                <div class="bg-[#2F3E46] rounded-lg p-8 text-center">
                    <div class="mb-6">
                        <div class="flex items-center justify-center mb-4">
                            <h3 class="text-2xl font-bold text-white font-montserrat">Agent Bookr</h3>
                        </div>
                        <div class="flex items-center justify-center text-white mb-4">
                            <i class="fas fa-play mr-2 text-[#FFB703]"></i>
                            <span class="text-sm font-montserrat">WATCH DEMO</span>
                        </div>
                        <p class="text-[#FFB703] font-semibold font-montserrat">the lead generation experts</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <!-- <section class="bg-[#CAD2C5] py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-black mb-4">Complete Lead Generation Platform</h2>
            </div>
            
                         <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8"> -->
                <!-- Lead Generation -->
                 <!-- <div class="service-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');">
                     </div>
                     <div class="p-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-4 font-montserrat">Lead Generation</h3>
                         <p class="text-[#2F3E46] mb-6 text-base leading-relaxed font-montserrat">
                            Generate high-quality leads with our advanced targeting tools. Access comprehensive property databases, owner information, and market insights to build your prospect list faster than ever.
                         </p>
                         <a href="#" class="inline-flex items-center text-[#2F3E46] font-semibold hover:text-[#52796F] transition-colors font-montserrat">
                             LEARN MORE
                             <i class="fas fa-arrow-right ml-2 text-[#2F3E46]"></i>
                         </a>
                     </div>
                 </div> -->

                <!-- Cold Calling Scripts -->
                 <!-- <div class="service-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');">
                     </div>
                     <div class="p-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-4 font-montserrat">Cold Calling Scripts</h3>
                         <p class="text-[#2F3E46] mb-6 text-base leading-relaxed font-montserrat">
                            Proven scripts that convert. Our library of cold calling scripts is designed by top-performing agents and tested across thousands of calls. Close more deals with confidence.
                         </p>
                         <a href="#" class="inline-flex items-center text-[#2F3E46] font-semibold hover:text-[#52796F] transition-colors font-montserrat">
                             LEARN MORE
                             <i class="fas fa-arrow-right ml-2 text-[#2F3E46]"></i>
                         </a>
                     </div>
                 </div> -->

                <!-- Tutorials & Training -->
                 <!-- <div class="service-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');">
                     </div>
                     <div class="p-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-4 font-montserrat">Tutorials & Training</h3>
                         <p class="text-[#2F3E46] mb-6 text-base leading-relaxed font-montserrat">
                            Master the art of lead generation with our comprehensive video tutorials and training programs. Learn from industry experts and get step-by-step guidance on every aspect of your business.
                         </p>
                         <a href="#" class="inline-flex items-center text-[#2F3E46] font-semibold hover:text-[#52796F] transition-colors font-montserrat">
                             LEARN MORE
                             <i class="fas fa-arrow-right ml-2 text-[#2F3E46]"></i>
                         </a>
                     </div>
                 </div> -->

                <!-- CRM Integration -->
                 <!-- <div class="service-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');">
                     </div>
                     <div class="p-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-4 font-montserrat">CRM Integration</h3>
                         <p class="text-[#2F3E46] mb-6 text-base leading-relaxed font-montserrat">
                            Seamlessly integrate with your existing CRM system. Automate lead capture, follow-up sequences, and client management to streamline your workflow and boost productivity.
                         </p>
                         <a href="#" class="inline-flex items-center text-[#2F3E46] font-semibold hover:text-[#52796F] transition-colors font-montserrat">
                             LEARN MORE
                             <i class="fas fa-arrow-right ml-2 text-[#2F3E46]"></i>
                         </a>
                     </div>
                 </div>
             </div>
        </div>
    </section> -->

    <!-- Pricing Section -->
    <section id="pricing" class="bg-[#2F3E46] py-16 relative overflow-hidden">
        <!-- Geometric Pattern Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, #333 0px, #333 2px, transparent 2px, transparent 8px), repeating-linear-gradient(-45deg, #333 0px, #333 2px, transparent 2px, transparent 8px); background-size: 16px 16px;"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4 font-montserrat">
                    Choose Your Plan
                    </h2>
                    <p class="text-white text-lg font-montserrat">
                    Start with a 14-day free trial. No credit card required.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Starter Plan -->
                <div class="bg-white rounded-lg p-8 shadow-lg relative">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-2">Starter</h3>
                        <div class="text-4xl font-bold text-[#2F3E46] mb-2">$29<span class="text-lg text-gray-500">/month</span></div>
                        <p class="text-gray-600">Perfect for new agents</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>500 leads per month</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Basic cold calling scripts</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Email support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Basic tutorials</span>
                        </li>
                    </ul>
                    <button class="w-full bg-[#FFB703] text-[#2F3E46] py-3 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-colors">
                        Start Free Trial
                    </button>
                </div>
                
                <!-- Professional Plan -->
                <div class="bg-white rounded-lg p-8 shadow-lg relative border-2 border-[#FFB703] transform scale-105">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-[#FFB703] text-[#2F3E46] px-4 py-1 rounded-full text-sm font-semibold">MOST POPULAR</span>
                    </div>
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-2">Professional</h3>
                        <div class="text-4xl font-bold text-[#2F3E46] mb-2">$79<span class="text-lg text-gray-500">/month</span></div>
                        <p class="text-gray-600">For growing teams</p>
                        </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>2,000 leads per month</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Advanced cold calling scripts</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Priority support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Full tutorial library</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>CRM integration</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Analytics dashboard</span>
                        </li>
                    </ul>
                    <button class="w-full bg-[#FFB703] text-[#2F3E46] py-3 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-colors">
                        Start Free Trial
                        </button>
                        </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-lg p-8 shadow-lg relative">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-[#2F3E46] mb-2">Enterprise</h3>
                        <div class="text-4xl font-bold text-[#2F3E46] mb-2">$199<span class="text-lg text-gray-500">/month</span></div>
                        <p class="text-gray-600">For large agencies</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Unlimited leads</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Custom scripts & training</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Dedicated account manager</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>White-label solutions</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>API access</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-[#52796F] mr-3"></i>
                            <span>Custom integrations</span>
                        </li>
                    </ul>
                    <button class="w-full bg-[#FFB703] text-[#2F3E46] py-3 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-colors">
                        Start Free Trial
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-black mb-4 font-montserrat">Proven Results. Guaranteed Growth.</h2>
                <p class="text-xl text-gray-600 font-montserrat">Our platform is built on these core principles:</p>
            </div>
            
            <!-- Top Row - 3 Values -->
            <div class="flex justify-center mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-4xl">
                    <!-- Proven Scripts -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#52796F] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#2F3E46] mb-2 font-montserrat">Proven Scripts</h3>
                    </div>
                    
                    <!-- Data-Driven Insights -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#52796F] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#2F3E46] mb-2 font-montserrat">Data-Driven Insights</h3>
                    </div>
                    
                    <!-- Expert Training -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#52796F] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#2F3E46] mb-2 font-montserrat">Expert Training</h3>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Row - 2 Values -->
            <div class="flex justify-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-2xl">
                    <!-- 24/7 Support -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#52796F] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-headset text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#2F3E46] mb-2 font-montserrat">24/7 Support</h3>
                    </div>
                    
                    <!-- Scalable Growth -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-[#52796F] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-rocket text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#2F3E46] mb-2 font-montserrat">Scalable Growth</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="bg-[#CAD2C5] py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- First Review Card - Google -->
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-[#52796F] rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">G</span>
                        </div>
                        <span class="font-semibold text-[#2F3E46]">Sarah M.</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-[#2F3E46] mb-4 font-montserrat">
                        Agent Bookr transformed my business! The cold calling scripts are gold and I've doubled my leads in just 3 months.
                    </p>
                    <div class="text-right">
                        <a href="#" class="text-[#52796F] hover:text-[#2F3E46] font-semibold font-montserrat">View Review</a>
                    </div>
                </div>

                <!-- Second Review Card - Google -->
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-[#52796F] rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">G</span>
                        </div>
                        <span class="font-semibold text-[#2F3E46]">Mike R.</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-[#2F3E46] mb-4 font-montserrat">
                        The lead generation tools are incredible. I'm closing deals I never thought possible. Best investment for my agency.
                    </p>
                    <div class="text-right">
                        <a href="#" class="text-[#52796F] hover:text-[#2F3E46] font-semibold font-montserrat">View Review</a>
                    </div>
                </div>

                <!-- Third Review Card - Facebook -->
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-[#52796F] rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">f</span>
                        </div>
                        <span class="font-semibold text-[#2F3E46]">Jennifer L.</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-[#2F3E46] mb-4 font-montserrat">
                        The tutorials are game-changing. I went from struggling with cold calls to closing 5 deals this month!
                    </p>
                    <div class="text-right">
                        <a href="#" class="text-[#52796F] hover:text-[#2F3E46] font-semibold font-montserrat">View Review</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Awards and Achievements Section -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Main Content -->
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-black mb-6 font-montserrat">
                        See Why <span class="text-gray-600">Agent Bookr</span> is the #1 Lead Generation Platform
                    </h2>
                    <p class="text-lg text-[#2F3E46] leading-relaxed font-montserrat mb-8">
                        Trusted by thousands of real estate agents nationwide, Agent Bookr has helped generate over $2 billion in closed deals. Our proven system delivers results that speak for themselves.
                    </p>
                    <button class="bg-[#FFB703] text-[#2F3E46] px-8 py-4 rounded-lg font-semibold hover:bg-[#FFB703]/90 transition-colors uppercase tracking-wide font-montserrat">
                        See Success Stories
                    </button>
                </div>
                
                <!-- Right Side - Awards and Recognitions -->
                <div class="space-y-8">
                    <!-- Industry Recognition -->
                     <div class="space-y-4">
                         <div class="flex items-start space-x-6">
                             <div>
                                <h3 class="text-3xl font-bold text-black font-montserrat">Real Estate Tech</h3>
                                <p class="text-black font-montserrat">Innovation Award 2024</p>
                             </div>
                         </div>
                         
                         <!-- Blue Border Separator -->
                         <div class="border-t-2 border-[#52796F] my-4"></div>
                         
                         <div class="text-[#52796F]">
                            <p class="font-bold text-lg">Best Lead Generation Platform</p>
                            <p class="text-sm">10,000+ Active Users</p>
                            <p class="text-sm">$2B+ in Closed Deals</p>
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="bg-[#CAD2C5] py-16">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="mb-12">
                <h2 class="text-4xl font-bold text-black font-montserrat">The Agent Bookr Blog</h2>
            </div>
            
            <!-- Blog Posts Grid -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left Column - Large Blog Post -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="h-64 bg-cover bg-center bg-blue-900">
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-[#2F3E46] mb-2 font-montserrat">Jul 21, 2025</div>
                        <h3 class="text-xl font-bold text-[#2F3E46] mb-3 font-montserrat">5 Cold Calling Scripts That Convert 3x More Leads</h3>
                        <p class="text-[#2F3E46] mb-4 font-montserrat">
                            Cold calling doesn't have to be intimidating. With the right scripts and approach, you can turn cold prospects into hot leads. We've analyzed thousands of successful calls to create scripts that work. Here are the top 5 proven scripts that our users report converting 3x more leads...
                        </p>
                        <a href="#" class="text-[#52796F] hover:text-[#2F3E46] font-semibold font-montserrat">Read More</a>
                    </div>
                </div>
                
                <!-- Right Column - Two Smaller Blog Posts -->
                <div class="space-y-8">
                    <!-- Top Right Blog Post -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-md">
                        <div class="h-32 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');">
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-[#2F3E46] mb-2 font-montserrat">Jul 7, 2025</div>
                            <h3 class="text-lg font-bold text-[#2F3E46] mb-3 font-montserrat">The Ultimate Guide to Lead Generation for Real Estate Agents</h3>
                            <p class="text-[#2F3E46] mb-4 font-montserrat">
                                Lead generation is the lifeblood of any successful real estate business. But with so many strategies out there, it can be overwhelming to know where to start. In this comprehensive guide, we'll walk you through the most effective lead generation techniques...
                            </p>
                            <a href="#" class="text-[#52796F] hover:text-[#2F3E46] font-semibold font-montserrat">Read More</a>
                        </div>
                    </div>
                    
                    <!-- Bottom Right Blog Post -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-md">
                        <div class="h-32 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');">
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-[#2F3E46] mb-2 font-montserrat">Jun 21, 2025</div>
                            <h3 class="text-lg font-bold text-[#2F3E46] mb-3 font-montserrat">How to Build a High-Converting Real Estate Funnel</h3>
                            <p class="text-[#2F3E46] mb-4 font-montserrat">
                                A well-designed sales funnel can be the difference between struggling to find leads and having prospects lining up to work with you. Learn how to create a funnel that converts visitors into clients and clients into repeat customers...
                            </p>
                            <a href="#" class="text-[#52796F] hover:text-[#2F3E46] font-semibold font-montserrat">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tenant Placement Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Image -->
                <div class="relative">
                    <div class="bg-gray-200 h-96 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2073&q=80" 
                             alt="Professional tenant placement services" 
                             class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Right Column - Content -->
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold text-black leading-tight">
                        Ready to Scale<br>
                        Your Real Estate<br>
                        Business?
                    </h2>
                    
                    <p class="text-lg text-black leading-relaxed">
                        Join thousands of successful real estate agents who have transformed their 
                        businesses with Agent Bookr. Our comprehensive platform provides everything 
                        you need to generate more leads and close more deals.
                    </p>
                    
                    <div class="pt-4">
                        <a href="#pricing" class="inline-flex items-center px-8 py-4 bg-[#FFB703] text-[#2F3E46] font-semibold rounded-lg hover:bg-[#FFB703]/90 transition duration-300 shadow-lg">
                            START YOUR FREE TRIAL
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
