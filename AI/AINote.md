# 数据标注 Data Label

## 以Qwen2.5-Coder-32B-Instruct为例

Qwen2.5-Coder-32B-Instruct 是一个专为代码生成与编程任务优化的语言模型，其参数量为 320亿（32B）。它基于 Qwen2.5 架构，在代码理解、生成和调试方面表现优异，支持多种编程语言，适用于开发辅助与自动化编程任务。

Demo地址https://www.modelscope.cn/docs/model-service/API-Inference/intro

部署Anaconda虚拟环境，下载完整模型。
```shell
conda create -n modelscope python==3.11    # 创建虚拟环境
conda activate modelscope                  # 激活虚拟环境
pip install modelscope                     # 安装modelscope
pip install torch torchvision torchaudio   # 安装PyTorch
pip install openai     # 当前魔搭平台的API-Inference，针对大语言模型提供OpenAI API兼容的接口。 对于LLM模型的API，使用前，请先安装OpenAI SDK
pip install anthropic  # 针对LLM模型，API-Inference也支持与Anthropic API兼容的调用方式。要使用Anthropic模式，请在使用前，安装Anthropic SDK
```

Demo代码如下